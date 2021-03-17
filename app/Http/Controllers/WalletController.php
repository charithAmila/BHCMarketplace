<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Models\User;
use Auth;

class WalletController extends Controller
{
    public function index(){
        if (Gate::allows('logged-in')) {
            return redirect(route('marketplace'));
        }
    	return view('wallet.index');
    }

    public function fetch(){
        $users = User::all();
        foreach ($users as $key => $user) {
            $asset_url = 'storage/user/photo/'.$user->display_photo;
            if ($user->display_photo == 'default.png') {
                $asset_url = 'user/photo/'.$user->display_photo;
            }
            $name = $user->name != null ? $user->name : $user->wallet;
            $links[] = [
                'name' => $name,
                'asset_url' => $asset_url,
                'customLink' => $user->signedLoginUrl($user->id),
            ];
        }
        $links = json_decode(json_encode($links));

        return $links;
    }

    public function customLogin($id){
    	$user = User::find($id);
    	Auth::login($user);
    	return redirect()->route('marketplace');
    }

}
