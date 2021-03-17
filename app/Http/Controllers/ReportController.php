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
    	$request->validate([
            'type' => 'required',
            'report_slug' => 'required',
            'description' => 'required',
        ]);

        $report = new Report;
        $report->type = $request->input('type');
        $report->user_id = Auth::user()->id;

        $inputType = $request->input('type');
        $reportSlug = $request->input('report_slug');
        $report_id = 0;
        if ($inputType == 'nft') {
            $collectible = Collectible::where('slug', $reportSlug)->first();
            $report_id = $collectible->id;
        }
        elseif ($inputType == 'user') {
            $user = User::where('short_url', $reportSlug)->orWhere('wallet', $reportSlug)->first();
            $report_id = $user->id;
        }
        else{
            $collection = Collection::where('short_url', $reportSlug)->first();
            $report_id = $collection->id;
        }

        $report->report_id = $report_id;
        $report->description = $request->input('description');
        $report->save();

        return response()->json([
            'message'   => 'Report submitted!',
        ]);
    }
}
