<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Report;
use App\Models\Collectible;
use App\Models\User;
use App\Models\Collection;
use Auth;

class ReportController extends Controller
{

    public function report(Request $request){
    	$data = new Report;
        $data->user_id = $request->user_id;
        $data->owner = $request->owner;
        $data->contract = $request->contract;
        $data->token_id = $request->token_id;
        $data->message = $request->message;
        $data->save();
        return true;
    }
    public function index(Request $request)
    {
        $data = Report::all();
        return $data;
    }

    public function show(Request $request,$contract,$token_id)
    {
    $data = Report::where('contract',$contract)->where('token_id',$token_id)->where('reported',true)->first();
    if($data){
        return true;
    }
    else{
        return false;
    }
    
}
}
