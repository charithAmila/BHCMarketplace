<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profile;
use App\Classes\CheckSign;
use Pelieth\LaravelEcrecover\EthSigRecover;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $profiles = Profile::all();
        return $profiles;
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
        $request->validate([
            'address' => 'required',
            'sign' => 'required',
            'ipfs_hash' => 'required',
            'short_url' => 'required|max:20'
        ]);
        $checker = new CheckSign;
        $message = "I agree to update my profile.";
        $granted = $checker->checkSign($message, $request->sign, $request->address);
        $profile = new Profile;
        $profile->address = $request->address;
        $profile->ipfs_hash = $request->ipfs_hash;
        $profile->short_url = $request->short_url;
        if ($granted) {
            $profile->save();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($address)
    {
        $profile = Profile::where('address', $address)->firstOrFail();
        if ($profile) {
            return $profile;
        } else {
            return false;
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $address)
    {
        $request->validate([
            'sign' => 'required',
            'ipfs_hash' => 'required',
            'short_url' => 'required'
        ]);
        $checker = new CheckSign;
        $message = "I agree to update my profile.";
        $granted = $checker->checkSign($message, $request->sign, $address);
        $profile = Profile::where('address', $address)->first();
        if ($granted) {
            if ($profile == null) {
                $profile = new Profile;
                $profile->address = $address;
                $profile->ipfs_hash = $request->ipfs_hash;
                $profile->short_url = $request->short_url;
                $profile->save();
            } else {
                $profile->ipfs_hash = $request->ipfs_hash;
                $profile->short_url = $request->short_url;
                $profile->update();
            }
            return response()->json(['success' => true]);
        }
        return abort(403);
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