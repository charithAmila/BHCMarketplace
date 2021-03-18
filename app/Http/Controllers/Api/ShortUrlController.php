<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;

class ShortUrlController extends Controller
{
    public function index(){
        return Profile::pluck('short_url');
    }

}
