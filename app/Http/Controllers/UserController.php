<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Auth;

use App\Models\User;
use App\Models\Collectible;
use App\Models\Legend;
use App\Models\Ownership;
use App\Models\Like;
use App\Models\Record;
use App\Models\Profile;

class UserController extends Controller
{
    public function index($slug){
    	$user = Profile::where('short_url', $slug)->first();
        // dd($user);

        /*$thisUser = new User;
        $this->data['user'] = json_decode(json_encode($thisUser->getUserInfo($user)));

        $this->data['following'] = $thisUser->followingUser($user->id);
    	$this->data['length'] = strlen($this->data['user']->name);*/
        if($user){
            $this->data['address'] = $user['address'];
        }
        else{
        $this->data['address']=$slug;
        }
    	return view('user.index', $this->data);
    }

    public function getAuthenticated(){
        $user = User::find(Auth::user()->id);
        return response()->json([
            'user' => $user
        ]);
    }

    public function getUserNft($user_slug, $filter){
        $user = User::where('short_url', $user_slug)->orWhere('wallet', $user_slug)->first();
        $isp = 0;
        $created = 0;
        $liked = 0;
        if ($filter == 'on-sale') {
            $isp = 1;
        }
        if ($filter == 'created') {
            $created = 1;
        }
        if ($filter == 'liked'){
            $liked = 1;
        }
        $nft = new Collectible;
        $data = $nft->filterQuery($user->id, $isp, $created, $liked);
        $collectibles = json_decode(json_encode($data));
        return response()->json([
            'collectibles' => $collectibles,
        ]);
    }


    public function create(){
        $user = new User;
        $user->wallet = Str::random(20);
        $user->display_photo = 'default.png';
        $user->cover_photo = 'default.jpeg';
        $user->save();
        Auth::login($user);
        return redirect()->route('marketplace');
    }

    public function filter($filter, $slug){
        $user = User::where('short_url', $slug)->orWhere('wallet', $slug)->first();
        $isp = 0;
        $items = Ownership::where('creator_id', $user->id)->orWhere('owner_id', $user->id)->get();

        if ($filter == 'on-sale') {
            $isp = 1;
        }
        if ($filter == 'collectibles') {
           
        }
        if ($filter == 'created') {
           $items = Ownership::where('creator_id', $user->id)->where('owner_id', '!=', $user->id)->get();
        }

        if($filter == 'liked'){
            $items = Like::where('user_id', $user->id)->get();
        }

        $collectible = new Collectible;
        $collectibles = $collectible->getCollectibles($items, $isp);
        $collectibles = json_decode(json_encode($collectibles));

        return response()->json([
            'collectibles' => $collectibles,
        ]);

    }

    public function fetch(){
        $user = User::find(Auth::user()->id);
        $photo_path = $user->display_photo == 'default.png' ? 'user/photo/' : 'storage/user/photo/';

        $user = [
            'name' => $user->name,
            'description' => $user->description,
            'short_url' => $user->short_url,
            'display_photo' => $user->display_photo,
            'photo_path' => $photo_path,
        ];
        return response()->json([
            'user' => $user,
        ]);
    }

    public function show(){

    }

    public function edit(User $user){

    }

    public function update(Request $request){
        $request->validate([
            'display_photo' => 'nullable|mimes:gif,png,jpeg,jpg',
            'name' => 'required_if:uploaded,dp',
            'short_url' => 'required_if:uploaded,dp',
            'cover_photo' => 'required_if:uploaded,cover'
            ]);

        $user = User::find(Auth::user()->id); // Update when connect wallet is done

        $new_name = $user->display_photo;

        if ($request->input('uploaded') == 'cover') {
            $new_name = $user->cover_photo;
        }
        

        if ($request->hasFile('display_photo')) {
            $image = $request->file('display_photo');
            $new_name = 'profile-'.rand(). date("YmdHm") .'.' . $image->getClientOriginalExtension();

            $imagePath = $request->file('display_photo')->storeAs('public/user/photo',  $new_name);
            $imagePath = explode('/',$imagePath);
            $imagePath = $imagePath[3];

            $user->display_photo = $imagePath;
        }

        if ($request->hasFile('cover_photo')) {
            $image = $request->file('cover_photo');
            $new_name = 'cover-'.rand(). date("YmdHm") .'.' . $image->getClientOriginalExtension();

            $imagePath = $request->file('cover_photo')->storeAs('public/user/cover',  $new_name);
            $imagePath = explode('/',$imagePath);
            $imagePath = $imagePath[3];

            $user->cover_photo = $imagePath;
        }

        if ($request->input('uploaded') == 'dp') {
            $user->name = $request->input('name');
            $user->description = $request->input('description');
            $user->short_url = $request->input('short_url');
        }

        $user->update();

        return response()->json([
            'message'   => 'Preferences updated successfully!',
            'photo' => $new_name,
            'name' => $user->name,
            'description' => $user->description,
        ]);
    }

    public function follower($short_url){
        $user = User::where('short_url', $short_url)->orWhere('wallet', $short_url)->first();
        $follower = DB::table('followers')->where('user_id', '=', $user->id)->where('follower_id', '=', Auth::user()->id)->first();

        if ($follower) {
            DB::table('followers')->where('user_id', '=', $user->id)->delete();
        }
        else{
            DB::table('followers')->insert([
                'user_id' => $user->id,
                'follower_id' => Auth::user()->id,
            ]);
            
        }

        return response()->json([
            'follower' => $user->id,
        ]);

    }

    public function getFollowing($short_url, $filter){

        if ($filter == 'following') {
            $filterColumn = 'follower_id';
        }else{
            $filterColumn = 'user_id';
        }

        $thisUser = new User;
        $user = User::where('short_url', $short_url)->orWhere('wallet', $short_url)->first();
        $following = DB::table('followers')->where($filterColumn, '=', $user->id)->get();

        $data = [];
        foreach ($following as $item) {
            $followers = count(DB::table('followers')->where('user_id', '=', $item->user_id)->get());
            if ($filter == 'following') {
                $current_user = User::find($item->user_id);
            }else{
                $current_user = User::find($item->follower_id);
            }

            $data[] = [
                'followers' => $followers,
                'asset_url' => $thisUser->getDisplayPhoto($current_user->display_photo),
                'link_profile' => $current_user->short_url != null ? $current_user->short_url : $current_user->wallet,
                'user_name' => $current_user->name != null ? $current_user->name : $current_user->wallet,
            ];
        }

        return response()->json([
            'user_follow' => $data,
        ]);
    }

}
