<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Bidding_Tokens;
use App\Classes\CheckSign;
class BidController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Bid::all();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allBids(Request $request)
    {
        $request->validate([
            'token_type' => 'required',
            'collection_type' => 'required',
            'collection_id'=> 'required',
            'token_id' => 'required',
        ]);
        $data = Bid::where(['token_type'=>$request->token_type,'collection_type'=>$request->collection_type,'collection_id'=>$request->collection_id,'token_id'=>$request->token_id])->get();
        return $data;
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'user_id' => 'required',
            'token_type' => 'required',
            'collection_type' => 'required',
            'collection_id'=> 'required',
            'token_id' => 'required',
            'bidding_token' => 'required',
            'bidding_amount' => 'required',
            'signature' => 'required'
        ]);
        $checker = new CheckSign;
        $message = "Place a Bid";
        $granted = $checker->checkSign($message, $request->signature, $request->address);
        $bid = new Bid;
        $bid->user_id = $request->user_id;
        $bid->token_type = $request->token_type;
        $bid->collection_type = $request->collection_type;
        $bid->collection_id = $request->collection_id;
        $bid->token_id = $request->token_id;
        $bid->bidding_token = $request->bidding_token;
        $bid->bidding_amount = $request->bidding_amount;
        if ($granted) {
            $bid->save();
            return true;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function startBid(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'user_id' => 'required',
            'token_type' => 'required',
            'collection_type' => 'required',
            'collection_id'=> 'required',
            'token_id' => 'required',
            'bidding_status' => 'required',
            'signature' => 'required'
        ]);
        
        $checker = new CheckSign;
        $message = "Allow bidding for token";
        $granted = $checker->checkSign($message, $request->signature, $request->address);
        $available = Bidding_Tokens::where(['user_id' => $request->user_id,'token_type' => $request->token_type,
        'collection_id'=> $request->collection_id,'token_id' =>$request->token_id])->exists();
        $bidding_token = new Bidding_Tokens;
        $bidding_token->user_id = $request->user_id;
        $bidding_token->token_type = $request->token_type;
        $bidding_token->collection_type = $request->collection_type;
        $bidding_token->collection_id = $request->collection_id;
        $bidding_token->token_id = $request->token_id;
        $bidding_token->bidding_status = $request->bidding_status;
        if($granted){
            if($available){
                
            $res =  Bidding_Tokens::where(['user_id' => $request->user_id,'token_type' => $request->token_type,
                'collection_id'=> $request->collection_id,'token_id' =>$request->token_id])->update(['bidding_status'=>true]);
                return $res;
                }
                else{
            $bidding_token->save();
            return true;
        }
        }

        return false;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function endBid(Request $request)
    {
        $request->validate([
            'address' => 'required',
            'index' => 'required',
            'signature'=>'required'
            ]);
            $checker = new CheckSign;
            $message = "Stop bidding for token";
            $granted = $checker->checkSign($message, $request->signature, $request->address);
            if ($granted) {
                $item = Bidding_Tokens::where( 'index' , $request->index)->update(['bidding_status'=>false]);
            }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
