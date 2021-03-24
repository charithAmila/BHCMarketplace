<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Collectible;
use App\Models\User;
use App\Models\Collection;
use App\Models\Legend;
use App\Models\Category;
use App\Models\Ownership;
use App\Models\Transaction;
use App\Models\Like;
use App\Models\Record;
use DB;
use Auth;

class IndexController extends Controller
{

    public function __construct(){
        $this->data['collectibles'] = [];
    }

    public function home(){
        return view('homepage');
    }

    public function index(){
        $collectibles = Collectible::all();

        $nft = new Collectible;
        $data = $nft->getUserList('all', 'sell');

    	return view('index', $this->data);
    }

    public function fetchCollectibles(){
        $nft = new Collectible;

        $data = $nft->fetchAllCollectibles();
        $data = json_decode(json_encode($data));
        return $data;
    }

    public function show($collectible_info){
        //$nft = new Collectible;
        //$transac = new Transaction;
        
        //$collectible = Collectible::where('slug', $slug)->first();
        
        //$onWishList = false;
        //if (Auth::check()) {
         //   $like = Like::where('nft_id', $collectible->id)->where('user_id', Auth::user()->id)->first();
         //   if ($like != null) {
         //       $onWishList = true;
         //   }
        //}
        //$this->data['onWishList'] = $onWishList;

        
        //$thisData = $nft->fetchThisCollectible($user_slug, $slug, $collectible);
        //$this->data['collectible'] = json_decode(json_encode($thisData));


        //$transactions = $transac->getPastTransactions($collectible->id);
        //$this->data['transactions'] = json_decode(json_encode($transactions));
        $dataArray = explode(":",$collectible_info);
        $this->data["contract"]=$dataArray[0];
        $this->data["id"]=$dataArray[1];
        $this->data["owner"]=$dataArray[2];
    	return view('show', $this->data);
    }

    public function fetchShowNft($user_slug, $slug){
        $nft = new Collectible;
        $transac = new Transaction;
        
        $collectible = Collectible::where('slug', $slug)->first();

        $thisData = $nft->fetchThisCollectible($user_slug, $slug, $collectible);
        $this->data['collectible'] = json_decode(json_encode($thisData));


        $transactions = $transac->getPastTransactions($collectible->id);
        $this->data['transactions'] = json_decode(json_encode($transactions));

        return  response()->json([
            'collectible'   => $this->data['collectible'],
            'transactions' => $this->data['transactions']
        ]);
    }

    public function about(){
    	return view('bhc');
    }

    public function faq(){
        return view('faq');
    }

    public function fetch($slug){
        $collectible = Collectible::where('slug', $slug)->first();
        return response()->json([
            'collectible'   => $collectible,
        ]);
    }

    public function filterCategory($category, $sortBy, $order){

        $nft = new Collectible;

        $data = $nft->fetchAllCollectibles($category, $sortBy, $order);
        $data = json_decode(json_encode($data));

        return response()->json([
            'collectibles'   => $data,
        ]);
    }

    public function filterUser($type, $day){
        $nft = new Collectible;
        $data = $nft->getUserList($day, $type);

        return response()->json([
            'userList'   => $data,
        ]);
    }

}
