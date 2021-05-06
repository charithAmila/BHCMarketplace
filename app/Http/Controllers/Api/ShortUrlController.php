<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function index(Request $request){
        return Profile::where("short_url","!=",$request->short_url)->pluck('short_url');
    }

}
