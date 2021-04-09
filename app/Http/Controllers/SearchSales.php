<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Illuminate\Http\Request;

class SearchSales extends Controller
{
    public function search()
    {
        return Sales::all();
    }
}