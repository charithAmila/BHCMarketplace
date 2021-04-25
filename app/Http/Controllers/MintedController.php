<?php

namespace App\Http\Controllers;

use App\Models\minted;
use Illuminate\Http\Request;

class MintedController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $last = minted::latest()->first();
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
        $data = $request->mints;
        for($i=0;$i<count($data);$i++){
            minted::create([
                "block"=>$data[$i][0],
                "minter" => $data[$i][1],
                "collection" => $data[$i][2],
                "token_id" => $data[$i][3],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\minted  $minted
     * @return \Illuminate\Http\Response
     */
    public function show($minter)
    {
        $mints = minted::where("minter",$minter)->get();
        return $mints;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\minted  $minted
     * @return \Illuminate\Http\Response
     */
    public function edit(minted $minted)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\minted  $minted
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, minted $minted)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\minted  $minted
     * @return \Illuminate\Http\Response
     */
    public function destroy(minted $minted)
    {
        //
    }
}
