<?php

namespace App\Http\Controllers;

use App\Models\NftData;
use Illuminate\Http\Request;

class NftDataController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return NftData::all();
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
        $data = NftData::where("collection",$request->collection)->where("token_id",$request->token_id)->first();
       if($data==null){ NftData::create([
            "collection" =>$request->collection,
            "token_id" =>$request->token_id,
            "uri" =>$request->uri,
        ]);}
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NftData  $nftData
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$collection)
    {
        return NftData::where("collection",$collection)->where("token_id",$request->token_id)->first();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NftData  $nftData
     * @return \Illuminate\Http\Response
     */
    public function edit(NftData $nftData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\NftData  $nftData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, NftData $nftData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NftData  $nftData
     * @return \Illuminate\Http\Response
     */
    public function destroy(NftData $nftData)
    {
        //
    }
}
