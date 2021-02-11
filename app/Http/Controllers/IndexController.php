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
use DB;
use Auth;

class IndexController extends Controller
{

    public function __construct()
    {
        $this->data['collectibles'] = [];
    }

    public function home()
    {
        return view('homepage');
    }

    public function index()
    {

        $collectibles = Collectible::all();
        $nft = new Collectible;

        $data = $nft->getAllCollectibles($collectibles);
        $this->data['collectibles'] = json_decode(json_encode($data));

        $seller = $nft->getTopUsers('all', 'sell');
        $this->data['seller'] = json_decode(json_encode($seller));

        return view('index', $this->data);
    }

    public function show($slug)
    {
        $nft = new Collectible;
        $transac = new Transaction;
        $collectible = Collectible::where('slug', $slug)->first();
        $onWishList = false;
        if (Auth::check()) {
            $like = Like::where('nft_id', $collectible->id)->where('user_id', Auth::user()->id)->first();
            if ($like != null) {
                $onWishList = true;
            }
        }

        $this->data['onWishList'] = $onWishList;

        $data = $nft->getSpecificCollectible($collectible);
        $this->data['collectible'] = json_decode(json_encode($data));

        $transactions = $transac->getPastTransactions($collectible->id);
        $this->data['transactions'] = json_decode(json_encode($transactions));

        return view('show', $this->data);
    }

    public function about()
    {
        return view('bhc');
    }

    public function faq()
    {
        return view('faq');
    }

    public function fetch($slug)
    {
        $collectible = Collectible::where('slug', $slug)->first();
        return response()->json([
            'collectible'   => $collectible,
        ]);
    }

    public function filterCategory(Request $request, $category)
    {
        if ($category == 'all') {
            $collectibles = Collectible::all();
        } else {
            $collectibles = Collectible::where('category_id', $category)->get();
        }
        if ($request->input('sortBy') != '') {
            if ($request->input('order') == 'asc') {
                $collectibles = $collectibles->sortBy($request->input('sortBy'));
            } else {
                $collectibles = $collectibles->sortByDesc($request->input('sortBy'));
            }
        }

        $nft = new Collectible;

        $this->data['collectibles'] = $nft->getAllCollectibles($collectibles);

        return response()->json([
            'collectibles'   => $this->data['collectibles'],
        ]);
    }

    public function filterUser(Request $request)
    {
        $nft = new Collectible;
        $data = $nft->getTopUsers($request->input('day'), $request->input('type'));
        return $data;
    }
}