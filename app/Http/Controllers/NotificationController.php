<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    public function addNotification(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);
        if ($request->noBuy) {
            $notification = new Notification;
            $notification->user_id = $request->user_id;
            $notification->message = $request->message;
            $notification->currency = $request->currency;
            $notification->save();
        } else {
            $notification = new Notification;
            $notification->user_id = $request->seller_id;
            $notification->message = $request->message_seller;
            $notification->sell_amount = $request->amount;
            $notification->save();
            $notification2 = new Notification;
            $notification2->user_id = $request->buyer_id;
            $notification2->message = $request->message_buyer;
            $notification2->buy_amount = $request->amount;
            $notification2->currency = $request->currency;
            $notification2->save();
        }
    }

    public function getNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $data = Notification::where(['user_id' => $request->user_id])->get();
        return $data;
    }
    public function delete(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $data = Notification::where(['user_id' => $request->user_id]);
        $data->delete();
    }

    public function getData()
    {
        return  Notification::all();
    }
}