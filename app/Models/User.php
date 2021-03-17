<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\URL;

use DB;
use Auth;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function signedLoginUrl(int $user_id){
        return URL::temporarySignedRoute('custom.login', now()->addMinutes(60), [
            'user_id' => $user_id,
        ]);
    }

    public function getDisplayPhoto($display_photo){
        $asset_url = 'storage/user/photo/'.$display_photo;
        if ($display_photo == 'default.png') {
            $asset_url = 'user/photo/'.$display_photo;
        }

        return $asset_url;
    }

    public function getUserInfo($user){
        $photo_path = $user->display_photo == 'default.png' ? 'user/photo/' : 'storage/user/photo/';
        $cover_path = $user->cover_photo == 'default.jpeg' ? 'user/cover/' : 'storage/user/cover/';

        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'description' => $user->description,
            'short_url' => $user->short_url,
            'wallet' => $user->wallet,
            'display_photo' => $user->display_photo,
            'cover_photo' => $user->cover_photo,
            'photo_path' => $photo_path,
            'cover_path' => $cover_path,
            'link_profile' => $user->short_url != null ? $user->short_url : $user->wallet,
        ];

        return $data;
    }

    public function followingUser($user_id){
        $following = false;
        if (Auth::check()) {
            $findFollower = DB::table('followers')->where('user_id', '=', $user_id)->where('follower_id', '=', Auth::user()->id)->first();
            if ($findFollower) {
                $following = true;
            }

        }
        
        return $following;
    }

    public function setNotificationData($transactions){
        $data = [];
        $tModel = new Transaction;
        foreach ($transactions as $transac) {
            $context = '';
            if ($transac->type == 'bid') {
                if ($transac->sold == 0) {
                    $context = 'offered '.$transac->price.' '.$transac->currency.' for ';
                }else{
                    $context = 'purchased 1 edition for '.$transac->price.' '.$transac->currency.' of ';
                }
            }else{
                $context = 'purchased '.$transac->quantity.' edition of ';
            }

            $user = User::find($transac->user_id);
            $photo_path = $user->display_photo == 'default.png' ? \URL::to('/').'/user/photo/' : \URL::to('/').'/storage/user/photo/';
            $current_name = $user->name != null ? $user->name : $user->wallet;
            $profile_name = substr($current_name, 0, 11);
            $addStr = '';
            if (strlen($current_name) > 12) {
                $addStr = '...';
            }

            $nft = Collectible::find($transac->nft_id);

            $data[] = [
                'profile_link' => $user->short_url != null ? $user->short_url : $user->wallet,
                'profile_name' => $profile_name.$addStr,
                'display_photo' => $photo_path.$user->display_photo,
                'action' => $context,
                'nft' => $nft->name,
                'transaction_time' => $tModel->timeAgo($transac->created_at),
            ];
        }

        return $data;
    }

    
}
