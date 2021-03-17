<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\User;

use Auth;

class NotificationController extends Controller
{
    public function userNotifications(){
    	$userId = Auth::user()->id;

    	$transactions = Transaction::join('records', 'transactions.record_id', '=', 'records.id')
    								->where('records.owner_id', $userId)
    								->where('transactions.type', '!=', 'bidding')
    								->where('transactions.type', '!=', 'sell')
    								->orderBy('transactions.created_at', 'desc');

        $transactions = $transactions->get([
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

    	$user = new User;
    	$notifications = $user->setNotificationData($transactions);

        return response()->json([
            'notifications' => $notifications,
        ]);
    }
}
