<?php

namespace App\Http\Controllers;

use App\Models\Likes;
use Illuminate\Http\Request;
use App\Classes\CheckSign;

class LikesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $likes = Likes::all();
        return response()->json(['likes' => $likes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function unlike(Request $request)
    {
        Likes::where([
            ['address', '=', $request->address],
            ['contract', '=', $request->contract],
            ['token_id', '=', $request->token_id]
        ])->update(['liked' => false]);
        return response()->json(['success' => true]);
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
            "address" => "required",
            "contract" => "required",
            "token_id" => "required",
            "sign" => "required"
        ]);

        $liked = Likes::where(
            [
                ['address', '=', $request->address],
                ['contract', '=', $request->contract],
                ['token_id', '=', $request->token_id]
            ]
        )->first();
        if (
            $liked === null
        ) {
            $checker = new CheckSign;
            $message = "I would like to like token : " . $request->contract . $request->token_id;
            $granted = $checker->checkSign($message, $request->sign, $request->address);
            if ($granted) {
                Likes::create([
                    "address" => $request->address,
                    "contract" => $request->contract,
                    "token_id" => $request->token_id,
                ]);
                return response()->json(['success' => true]);
            } else {
                return abort(403);
            }
        } else {
            Likes::where([
                ['address', '=', $request->address],
                ['contract', '=', $request->contract],
                ['token_id', '=', $request->token_id]
            ])->update(['liked' => true]);
            return response()->json(['success' => true]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function show(Likes $likes)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function edit(Likes $likes)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Likes $likes)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Likes  $likes
     * @return \Illuminate\Http\Response
     */
    public function destroy(Likes $likes)
    {
        //
    }
}