<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Collectible;
use App\Models\Like;

use Auth;

class LikeController extends Controller
{
    public function wishlist($nft_slug){
    	$collectible = Collectible::where('slug', $nft_slug)->first();

    	$wishlist = Like::where('nft_id', $collectible->id)->where('user_id', Auth::user()->id)->first();

    	if ($wishlist == null) {
    		$wishlist = new Like;
	    	$wishlist->nft_id = $collectible->id;
	    	$wishlist->user_id = Auth::user()->id;
	    	$wishlist->save();
	    	$action = "added";
    	}
    	else{
    		$wishlist = Like::where('nft_id', $collectible->id)->where('user_id', Auth::user()->id)->first();
    		$wishlist->delete();
    		$action = "deleted";
    	}
    	return response()->json([
            'action'   => $action,
        ]);
    }
}
