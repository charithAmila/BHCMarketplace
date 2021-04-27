<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Carbon\Carbon;
use Auth;


class NotificationController extends Controller
{
    public function addNotification(Request $request)
    {
        if ($request->type =='bid') {
            $notification = new Notification;
            $notification->user_id = $request->user_id;
            $notification->message = $request->message_bidder;
            $notification->currency = $request->currency;
            $notification->owner = $request->owner;
            $notification->contract = $request->contract;
            $notification->token_id = $request->token_id;
            $notification->type = 'bid';
            $notification->save();
            $notification2 = new Notification;
            $notification2->user_id = $request->owner;
            $notification2->message = $request->message_owner;
            $notification2->currency = $request->currency;
            $notification2->owner = $request->owner;
            $notification2->contract = $request->contract;
            $notification2->token_id = $request->token_id;
            $notification2->type = 'bid';
            $notification2->save();
        } else if($request->type == 'sell'){
            $notification = new Notification;
            $notification->user_id = $request->seller_id;
            $notification->message = $request->message_seller;
            $notification->sell_amount = $request->amount;
            $notification->currency = $request->currency;
            $notification->owner = $request->buyer_id;
            $notification->contract = $request->contract;
            $notification->token_id = $request->token_id;
            $notification->type = 'sell';
            $notification->save();
            $notification2 = new Notification;
            $notification2->user_id = $request->buyer_id;
            $notification2->message = $request->message_buyer;
            $notification2->buy_amount = $request->amount;
            $notification2->currency = $request->currency;
            $notification2->owner = $request->buyer_id;
            $notification2->contract = $request->contract;
            $notification2->token_id = $request->token_id;
            $notification2->type = 'sell';
            $notification2->save();
        }
        elseif($request->type == 'create'){
            $notification = new Notification;
            $notification->user_id = $request->user_id;
            $notification->message = $request->message;
            $notification->type = 'create';
            $notification->save();
        }
        else if($request->type == 'follow'){
            $notification = new Notification;
            $notification->user_id = $request->user_id;
            $notification->message = $request->message;
            $notification->owner = $request->owner;
            $notification->type = 'follow';
            $notification->save();
        }
    }

    public function getNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);
        $data = Notification::where(['user_id' => $request->user_id,'status'=>true])->orderBy('created_at', 'DESC')->get();
        return $data;
    }
    public function delete(Request $request)
    {
        $request->validate([
            'user_id' => 'required'
        ]);

        $data = Notification::where(['user_id' => $request->user_id])->update(['status' =>false]);
       // $data->delete();
    }

    public function getData(Request $request, $time)
    {
        $time_duration = $time;
        if ($time_duration == "1") {
            return  Notification::whereDate('created_at', '>', Carbon::now()->subDays(1))->get();
        } else if ($time_duration == "7") {
            return  Notification::whereDate('created_at', '>', Carbon::now()->subDays(7))->get();
        } else if ($time_duration == "30") {
            return  Notification::whereDate('created_at', '>', Carbon::now()->subDays(30))->get();
        } else {
            return  Notification::all();
        }
    }
}