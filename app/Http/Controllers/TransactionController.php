<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\Ownership;
use App\Models\Collectible;
use App\Models\Record;
use App\Models\User;

use App\Events\PlaceBid;
use Auth;
use URL;

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

        // SEND NOTIFICATION
        $sentUser = Record::find($request->input('record_id'));
        $thisNotif = $this->getLatestTransaction($sentUser->owner_id);
        event(new PlaceBid($thisNotif, $sentUser->owner_id));
        // #################

        return response()->json([
            'message'   => $message,
        ]);

    }

    public function getLatestTransaction($userId){
        $transaction = Transaction::join('records', 'transactions.record_id', '=', 'records.id')
                                    ->where('records.owner_id', $userId)
                                    ->where('transactions.type', '!=', 'bidding')
                                    ->where('transactions.type', '!=', 'sell')
                                    ->orderBy('transactions.created_at', 'desc')
                                    ->take(1);

        $transaction = $transaction->get([
                                    'transactions.nft_id',
                                    'transactions.type',
                                    'transactions.quantity',
                                    'transactions.price',
                                    'transactions.currency',
                                    'transactions.sold',
                                    'transactions.user_id',
                                    'transactions.record_id',
                                    'transactions.created_at',
                                    'records.owner_id'
                                ]);
        $data = $this->callNotification($transaction[0]);
        return $data;
    }

    public function callNotification($transac){
        $data = [];
        $context = '';
        $tModel = new Transaction;
        

        $user = User::find($transac->user_id);
        $photo_path = $user->display_photo == 'default.png' ? \URL::to('/').'/user/photo/' : \URL::to('/').'/storage/user/photo/';
        $current_name = $user->name != null ? $user->name : $user->wallet;
        $profile_name = substr($current_name, 0, 11);
        $addStr = '';
        if (strlen($current_name) > 12) {
            $addStr = '...';
        }

        if ($transac->type == 'bid') {
            if ($transac->sold == 0) {
                $context = 'offered '.$transac->price.' '.$transac->currency.' for ';
            }else{
                $context = 'purchased 1 edition for '.$transac->price.' '.$transac->currency.' of ';
            }
        }else{
            $context = 'purchased '.$transac->quantity.' edition of ';
        }

        $nft = Collectible::find($transac->nft_id);

        $data= [
            'profile_link' => $user->short_url != null ? $user->short_url : $user->wallet,
            'profile_name' => $profile_name.$addStr,
            'display_photo' => $photo_path.$user->display_photo,
            'action' => $context,
            'nft' => $nft->name,
            'transaction_time' => $tModel->timeAgo($transac->created_at),
            'asset_url' => \URL::to('/').'/',
        ];
        return $data;
    }

    public function updateRecord($slug, $user_id){
        $nft = Collectible::where('slug', $slug)->first();
        $user = User::find($user_id);

        $record = Record::where('nft_id', $nft->id)->where('owner_id', $user->id)->first();
        $selling = $record->selling;
        $record->selling = $selling==1 ? 0 : 1;
        $record->save();
    }

    public function bidList($record_id){
        $bidList = Transaction::where('type', 'bid')
                                ->where('record_id', $record_id)
                                ->orderBy('price', 'desc')
                                ->get();

        $data = [];
        foreach ($bidList as $key => $bid) {
            $user = User::find($bid->user_id);
            $photo_path = $user->display_photo == 'default.png' ? 'user/photo/' : 'storage/user/photo/';
            $display_photo = $photo_path.$user->display_photo;
            $userData = [
                'name' => $user->name != null ? $user->name : $user->wallet,
                'display_photo' => $display_photo,
                'asset_url' => URL::to('/').'/',
                'link_profile' => $user->short_url != null ? $user->short_url : $user->wallet,
            ];
            $data[] = [
                'user' => $userData,
                'bid' => $bid
            ];
        }

        return response()->json([
            'list' => $data,
        ]);
    }

    public function acceptBid(Request $request){
        $transaction = Transaction::find($request->input('transaction_id'));
        $transaction->sold = 1;
        $transaction->save();

        $record = Record::find($transaction->record_id);
        $copies_left = $record->copies_left - 1;
        $copies = $record->copies;
        $record->copies_left = $copies_left;
        $record->save();

        $nft = Collectible::find($transaction->nft_id);


        $existingRecord = Record::where('nft_id', $nft->id)->where('owner_id', $transaction->user_id)->first();

        if($existingRecord == null){
            $newRecord = new Record;
            $newRecord->nft_id = $nft->id;
            $newRecord->owner_id = $transaction->user_id;
            $newRecord->copies = $copies;
            $newRecord->copies_left = 1;
            $newRecord->selling = $nft->isp;
            $newRecord->bidding = 1;
            $newRecord->save();
        }else{
            $copies_left = $existingRecord->copies_left + 1;
            $existingRecord->copies_left = $copies_left;
            $existingRecord->save(); 
        }


        $sentUser = Record::find($transaction->record_id);
        $thisNotif = $this->getLatestTransaction($sentUser->owner_id);
        event(new PlaceBid($thisNotif, $sentUser->owner_id));
    }
}
