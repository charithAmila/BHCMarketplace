<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collectible;
use App\Models\Like;

use Auth;

class LikeController extends Controller
{
    public function wishlist(Request $request){
        $nft_slug = $request->input('nft_slug');
        $record_id = $request->input('record_id');
    	$collectible = Collectible::where('slug', $nft_slug)->first();

    	$wishlist = Like::where('nft_id', $collectible->id)->where('user_id', Auth::user()->id)->where('record_id', $record_id)->first();

    	if ($wishlist == null) {
    		$wishlist = new Like;
	    	$wishlist->nft_id = $collectible->id;
            $wishlist->user_id = Auth::user()->id;
	    	$wishlist->record_id = $record_id;
	    	$wishlist->save();
	    	$action = "added";
    	}
    	else{
    		$wishlist->delete();
    		$action = "deleted";
    	}
    	return response()->json([
            'action'   => $action,
        ]);
    }
}
