<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subscribe;
class SubscribeController extends Controller
{
    function subscribe(Request $request){
        $new = new Subscribe;
        $new->email= $request->email;
        $new->save();

        return true;
    }
}
