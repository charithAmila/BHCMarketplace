<?php

namespace App\Http\Controllers;

use App\Models\Follower;
use App\Classes\CheckSign;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function index()
    {
        $followers = Follower::all();

        return response()->json(['followers' => $followers]);
    }
    public function unfollow(Request $request)
    {
        Follower::where([
            ['user_id', '=', $request->user_id],
            ['follower_id', '=', $request->follower_id],
        ])->update(['followed' => false]);
        return response()->json(['success' => true]);
    }
    public function follow(Request $request)
    {
        $request->validate([
            "user_id" => "required",
            "follower_id" => "required",
            "sign" => "required"
        ]);

        $followed = Follower::where(
            [
                ['user_id', '=', $request->user_id],
                ['follower_id', '=', $request->follower_id],
            ]
        )->first();
        if (
            $followed === null
        ) {
            $checker = new CheckSign;
            $message = "I would like to follow user : " . $request->user_id . $request->follower_id;
            $granted = $checker->checkSign($message, $request->sign, $request->follower_id);
            if ($granted) {
                Follower::create([
                    "user_id" => $request->user_id,
                    "follower_id" => $request->follower_id,
                    "followed" => true,
                ]);
                return response()->json(['success' => true]);
            } else {
                return abort(403);
            }
        } else {
            Follower::where([
                ['user_id', '=', $request->user_id],
                ['follower_id', '=', $request->follower_id],
            ])->update(['followed' => true]);
            return response()->json(['success' => true]);
        }
    }
}