<?php

namespace App\Http\Controllers;

use App\Models\Collections;
use Illuminate\Http\Request;

class CollectionsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $collections = Collections::all();
        return $collections;
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
     * @param  \App\Models\Collections  $collections
     * @return \Illuminate\Http\Response
     */
    public function show($address)
    {
        $collection = Collections::where("address", $address)->firstOrFail();
        return $collection;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collections  $collections
     * @return \Illuminate\Http\Response
     */
    public function edit(Collections $collections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collections  $collections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collections $collections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collections  $collections
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collections $collections)
    {
        //
    }
}
