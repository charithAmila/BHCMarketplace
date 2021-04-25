<?php

namespace App\Http\Controllers;

use App\Models\transfers;
use Illuminate\Http\Request;

class TransfersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $last = transfers::where("collection",$request->collection)->latest("id")->first();
        return $last;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->transfers;
        for($i=0;$i<count($data);$i++){
        $existingRecord = transfers::where("block",$data[$i][0])->where("collection",$data[$i][1])->where("owner",$data[$i][2])->where("token_id",$data[$i][3])->get();
           if(count($existingRecord)==0) {transfers::create([
                "block"=>$data[$i][0],
                "collection" => $data[$i][1],
                "owner" => $data[$i][2],
                "token_id" => $data[$i][3],
            ]);}
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\transfers  $transfers
     * @return \Illuminate\Http\Response
     */
    public function show($collection)
    {
 $transfs = transfers::where("collection",$collection)->get();
        return $transfs;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\transfers  $transfers
     * @return \Illuminate\Http\Response
     */
    public function edit(transfers $transfers)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\transfers  $transfers
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, transfers $transfers)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\transfers  $transfers
     * @return \Illuminate\Http\Response
     */
    public function destroy(transfers $transfers)
    {
        //
    }
}
