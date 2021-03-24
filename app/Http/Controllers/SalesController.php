<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;
use App\Classes\CheckSign;

class SalesController extends Controller
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
        $request->validate([
            "collection" => "required",
            "current_owner" => "required",
            "token_id" => "required",
            "price" => "required",
            'is_instant' => "required",
            "currency" => "required",
            "signature" => "required",
            "order_id" => "required"
        ]);
        $checker = new CheckSign;
        $message = $request->order_id;
        $granted = $checker->checkSign($message, $request->signature, $request->current_owner);
        if ($granted) {
            $order = Sales::create([
                "collection" => $request->collection,
                "current_owner" => $request->current_owner,
                "token_id" => $request->token_id,
                "price" => $request->price,
                "is_instant" => $request->is_instant,
                "currency" => $request->currency,
                "signature" => $request->signature,
            ]);
            return response()->json(["success" => true]);
        } else {
            return abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function show(Sales $sales)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function edit(Sales $sales)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Sales $sales)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Sales  $sales
     * @return \Illuminate\Http\Response
     */
    public function destroy(Sales $sales)
    {
        //
    }
}
