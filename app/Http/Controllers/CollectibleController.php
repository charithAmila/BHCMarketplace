<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image as Image;
use Auth;

use App\Models\Collection;
use App\Models\Collectible;
use App\Models\Category;
use App\Models\Legend;
use App\Models\Ownership;
use App\Models\Transaction;
use App\Models\Record;

class CollectibleController extends Controller
{
    public function index(){
        return view('collectible.create');
    }

    public function create($type){
        $collect = new Collection;
        $collections = $collect->getCollections();
        $this->data['collections'] = json_decode(json_encode($collections));

        $this->data['categories'] = Category::all();
        $this->data['legends'] = Legend::all();
        $this->data['type'] = $type == 'solo-collectible' ? 'solo' : 'multiple';
        
    	return view('collectible.create-collectible', $this->data);
    }

    public function store(Request $request){


        $validatedData = $request->validate([
            'nft' => 'required|file|max:51200|mimes:gif,png,jpeg,jpg,webp,mp4,mp3',
            'aop' => 'required',
            'aop_link' => 'required_if:aop,1',
            'collection_id' => 'required|numeric',
            'type' => 'required',
            'name' => 'required|unique:collectibles',
            'royalties' => 'required|numeric',
            'copies' => 'required_if:type,multiple',
            'pos' => 'required',
            'isp' => 'required',
            'price' => 'required_if:isp,1',
            'currency' => 'required',
            'category_id' => 'required',
            'legend_id' => 'required'
            ], [
            'nft.required' => 'Primary is required',
            'nft.mimes' => 'File attachment must be gif, png, jpeg, jpg, webp, mp4, mp3',
            'nft.size' => 'File attachment must be equal or less than 50mb',
            'aop.required' => 'Access once purchased is required',
            'aop_link.required_if' => 'Digital key or code to redeem is required',
            'collection_id.required' => 'Collection is required',
            'pos.required' => 'Put on sale is required',
            'isp.required' => 'Instant sale price is required',
            'price.required_if' => 'Price is required',
        ]);

        $collectible = new Collectible($validatedData);

        $image = $request->file('nft');
        $new_name = 'collectible-'.rand(). date("YmdHm") .'.' . $image->getClientOriginalExtension();
        $imagePath = $request->file('nft')->storeAs('public/collectibles',  $new_name);
        $imagePath = explode('/',$imagePath);
        $imagePath = $imagePath[2];

        $collectible->nft = $imagePath;
        $collectible->slug = Str::slug($request->input('name'), '-');
        $collectible->save();

        $ownership = new Ownership;
        $ownership->nft_id = $collectible->id;
        $ownership->creator_id = Auth::user()->id;
        $ownership->owner_id = Auth::user()->id;
        $ownership->seller_id = Auth::user()->id;
        $ownership->save();


        $record = new Record;
        $record->nft_id = $collectible->id;
        $record->owner_id = Auth::user()->id;
        $record->copies = $collectible->copies;
        $record->copies_left = $collectible->copies;
        $record->selling = $collectible->isp == 1 ? 1 : 0;
        $record->bidding = 1;
        $record->save();

        $transaction_type = $collectible->isp == 1 ? 'sell' : 'bidding';

        $transaction = new Transaction;
        $transaction->type = $transaction_type;
        $transaction->record_id = $record->id;
        $transaction->nft_id = $collectible->id;
        $transaction->user_id = Auth::user()->id;
        $transaction->price = $collectible->price;
        $transaction->currency = $collectible->currency;
        $transaction->sold = null;
        $transaction->save();

        return response()->json([
            'message'   => 'Collectible created successfully!',
        ]);
    }

    
}
