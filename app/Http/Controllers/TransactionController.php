<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\Ownership;
use App\Models\Collectible;
use App\Models\Record;
use App\Models\User;
use Auth;

class TransactionController extends Controller
{
    public function store(Request $request){
    	$transaction = new Transaction;
        $transaction->type = $request->input('type');
        $transaction->record_id = $request->input('record_id');
        $transaction->nft_id = $request->input('nft_id');
        $transaction->user_id = Auth::user()->id;
        $transaction->quantity = $request->input('quantity_input');
        $transaction->price = $request->input('price');
        $transaction->currency = $request->input('currency');
        $transaction->sold = false;
        $transaction->save();

        $message = 'Bid placed!';

        if ($request->input('type') == 'buy') {

            $collectible = Collectible::find($request->input('nft_id'));

            $quantity = intval($request->input('quantity_input'));

            $recordReferenced = Record::find($request->input('record_id'));
            $copies_left = $recordReferenced->copies_left;
            $recordReferenced->copies_left = $copies_left - $quantity;
            $recordReferenced->save();

            $ifRecordExist = Record::where('nft_id', $collectible->id)->where('owner_id', Auth::user()->id)->first();

            if ($ifRecordExist == null) {
                $record = new Record;
                $record->nft_id = $collectible->id;
                $record->owner_id = Auth::user()->id;
                $record->copies = $collectible->copies;
                $record->copies_left = $quantity;
                $record->selling = $collectible->isp == 1 ? 1 : 0;
                $record->bidding = 1;
                $record->save();
            }else{
                $current_copies = $ifRecordExist->copies_left;
                $total_copies = $current_copies + $quantity;
                $ifRecordExist->copies_left = $total_copies;
                $ifRecordExist->save();
            }

            


        	$owner = Ownership::where('nft_id', $request->input('nft_id'))->whereColumn('creator_id', 'owner_id')->first();
        	$ownership = new Ownership;
	        $ownership->nft_id = $request->input('nft_id');
	        $ownership->creator_id = $owner->creator_id;
            $ownership->owner_id = Auth::user()->id;
	        $ownership->seller_id = Auth::user()->id;
	        $ownership->save();

	        $message = 'NFT purchased';
        }

        return response()->json([
            'message'   => $message,
        ]);
    }

    public function updateRecord($slug, $user_id){
        $nft = Collectible::where('slug', $slug)->first();
        $user = User::find($user_id);

        $record = Record::where('nft_id', $nft->id)->where('owner_id', $user->id)->first();
        $selling = $record->selling;
        $record->selling = $selling==1 ? 0 : 1;
        $record->save();
    }
}
