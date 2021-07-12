<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Models\NftData;

use Illuminate\Http\Request;

class ExposeDataController extends Controller
{
     public function index()
    {
        $sales = Sales::all();
        return $sales;
    }

}
