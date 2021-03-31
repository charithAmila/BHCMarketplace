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
            'contract_address' => 'required',
            'owner'=> 'required',
            'token_id' => 'required',
        ]);
        $data = Bid::where(['contract_address'=>$request->contract_address,'owner'=>$request->owner,'token_id'=>$request->token_id])->get();
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
            'owner' => 'required',
            'bidding_address' => 'required',
            'contract_address' => 'required',
            'token_id' => 'required',
            'bidding_token' => 'required',
            'bidding_amount' => 'required',
            'signature' => 'required'
        ]);
        $checker = new CheckSign;
        $message = "Place a Bid";
        $granted = $checker->checkSign($message, $request->signature, $request->bidding_address);
        $bid = new Bid;
        $bid->owner = $request->owner;
        $bid->bidding_address = $request->bidding_address;
        $bid->contract_address = $request->contract_address;
        $bid->token_id = $request->token_id;
        $bid->bidding_token = $request->bidding_token;
        $bid->bidding_amount = $request->bidding_amount;
        $bid->signature = $request->signature;
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
            'owner' => 'required',
            'contract_address' => 'required',
            'token_id' => 'required',
            'bidding_status' => 'required',
            'signature' => 'required'
        ]);
        
        $checker = new CheckSign;
        $message = "Allow bidding for token";
        $granted = $checker->checkSign($message, $request->signature, $request->address);
        $available = Bidding_Tokens::where(['contract_address' => $request->contract_address,'token_id' => $request->token_id])->exists();
        $bidding_token = new Bidding_Tokens;
        $bidding_token->contract_address = $request->contract_address;
        $bidding_token->token_id = $request->token_id;
        $bidding_token->status = $request->bidding_status;
        if($granted){
            if($available){
            $res =  Bidding_Tokens::where([
                'contract_address'=> $request->contract_address,'token_id' =>$request->token_id])->update(['status'=>true]);
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
    public function getBiddingStatus(Request $request)
    {
        $request->validate([
            'owner' => 'required',
            'contract_address' => 'required',
            'token_id' => 'required',
        ]);
         $data = Bidding_Tokens::where(['contract_address'=>$request->contract_address,'owner'=>$request->owner,'token_id'=>$request->token_id])->get();
        return $data['bidding_status'];
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
