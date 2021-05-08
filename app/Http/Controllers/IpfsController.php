<?php

namespace App\Http\Controllers;

use App\Models\Ipfs;
use Illuminate\Http\Request;

class IpfsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ipfs  $ipfs
     * @return \Illuminate\Http\Response
     */
    public function show($hash)
    {
        $data = Ipfs::where("hash",$hash)->first();

        if($data==null){
            $cURLConnection = curl_init();

            curl_setopt($cURLConnection, CURLOPT_URL, 'http://bhc.mypinata.cloud/ipfs/'.$hash);
            curl_setopt($cURLConnection, CURLOPT_RETURNTRANSFER, true);

            $phoneList = curl_exec($cURLConnection);
            curl_close($cURLConnection);

            $jsonArrayResponse = json_decode($phoneList);
            Ipfs::create([
                "hash" => $hash,
                "data"=>$phoneList
            ]);
        }
        else{
            $phoneList = $data->data;
        }
        return $phoneList;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ipfs  $ipfs
     * @return \Illuminate\Http\Response
     */
    public function edit(Ipfs $ipfs)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ipfs  $ipfs
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Ipfs $ipfs)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ipfs  $ipfs
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ipfs $ipfs)
    {
        //
    }
}
