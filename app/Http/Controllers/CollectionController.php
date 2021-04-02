<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectionController extends Controller
{
    public function show($address)
    {
        $this->data["collection"] = $address;
        return view('collection.index', $this->data);
    }
}
