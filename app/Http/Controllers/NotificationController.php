<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use Auth;

class NotificationController extends Controller
{
    public function addNotification(Request $request){
         $request->validate([
            'user_id' => 'required',
            'message' => 'required',
        ]);
        $notification = new Notification;
        $notification->user_id = $request->user_id;
        $notification->message = $request->message;
    	$notification->save();
    }

    public function getNotification(Request $request){
          $request->validate([
            'user_id' => 'required',
        ]);
        $data = Notification::where(['user_id'=>$request->user_id])->get();
        return $data;
    }
    public function delete(Request $request){
        $request->validate([
            'user_id' => 'required'
        ]);

    $data = Notification::where(['user_id'=>$request->user_id]);
    $data->delete();
    }
}
