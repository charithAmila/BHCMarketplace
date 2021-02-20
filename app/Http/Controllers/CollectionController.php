<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Auth;

class CollectionController extends Controller
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
        $validatedData = $request->validate([
            'image' => 'required|mimes:gif,png,jpeg,jpg',
            'display_name' => 'required',
            'symbol' => 'required',
            ], [
            'image.mimes' => 'File attachment must be gif, png, jpeg, jpg',
            'display_name.required' => 'The display name is required',
        ]);

        $collection = new Collection;

        $image = $request->file('image');
        $new_name = 'collection-'.rand(). date("YmdHm") .'.' . $image->getClientOriginalExtension();
        $imagePath = $request->file('image')->storeAs('public/collections',  $new_name);
        $imagePath = explode('/',$imagePath);
        $imagePath = $imagePath[2];

        $collection->user_id = Auth::user()->id;
        $collection->image = $imagePath;
        $collection->display_name = $request->input('display_name');
        $collection->symbol = $request->input('symbol');
        $collection->description = $request->input('description');
        $collection->short_url = $request->input('short_url');

        $collection->save();

        return response()->json([
            'message'   => 'Collection created successfully!',
            'collection' => $collection,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Collection  $collection
     * @return \Illuminate\Http\Response
     */
    public function destroy(Collection $collection)
    {
        //
    }
}
