<?php

use App\Events\PlaceBid;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LikesController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\CollectibleController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CollectionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [IndexController::class, 'home'])->name('homepage');
Route::get('/nft', [IndexController::class, 'index'])->name('marketplace');
Route::get('/nft/fetchCollectibles', [IndexController::class, 'fetchCollectibles']);
Route::get('/nft/filter/{category}/{sortBy}/{order}', [IndexController::class, 'filterCategory']);
Route::get('/nft/user/filter/{type}/{day}', [IndexController::class, 'filterUser']);
Route::get('/nft/{collectible_info}', [IndexController::class, 'show'])->name('show.collectible');
Route::get('/update/show-page/{user_slug}/{slug}', [IndexController::class, 'fetchShowNft']);



Route::get('/nft/fetch/{slug}', [IndexController::class, 'fetch']);
Route::get('/bhc', [IndexController::class, 'about'])->name('about');
Route::get('/faq', [IndexController::class, 'faq'])->name('faq');

Route::get('/search', [SearchController::class, 'index'])->name('search');

Route::get('/create', [CollectibleController::class, 'index'])->name('create.collectible.choices');
Route::get('/create/{type}', [CollectibleController::class, 'create']);
Route::post('/create/collectible', [CollectibleController::class, 'store'])->name('create.collectible');



Route::get('/connect', [WalletController::class, 'index'])->name('connect.wallet');
Route::get('/wallet/fetch', [WalletController::class, 'fetch'])->name('wallet.fetch');

Route::middleware('signed')->prefix('/connect/{user_id}')->group(function () {
	Route::get('/', [WalletController::class, 'customLogin'])->name('custom.login');
});
Route::get('/profile/{slug}', [UserController::class, 'index'])->name('user.profile');
Route::get('/user/data', [UserController::class, 'getAuthenticated']);
Route::get('/profile/nft/fetch/{user_slug}/{filter}', [UserController::class, 'getUserNft']);
Route::get('users/create', [UserController::class, 'create'])->name('create.user');
Route::get('/user/fetch', [UserController::class, 'fetch']);
Route::get('/users/{filter}/{slug}', [UserController::class, 'filter']);
Route::post('/users/update', [UserController::class, 'update'])->name('user.update');
Route::post('/follower/{short_url}', [UserController::class, 'follower']);
Route::get('/user-follow/{short_url}/{filter}', [UserController::class, 'getFollowing']);



Route::get('/collection/{collection_slug}', [CollectionController::class, 'show']);
Route::get('/collection/{collection_slug}/{filter}', [CollectionController::class, 'filterCollection']);
Route::post('/collection', [CollectionController::class, 'store'])->name('create.collection');

Route::post('/wishlist', [LikesController::class, 'wishlist']);



Route::get('/logout', function () {
	Auth::logout();
	return redirect()->back();
})->name('disconnect');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/create/transaction', [TransactionController::class, 'store']);
Route::get('/update/nft/status/{slug}/{user_id}', [TransactionController::class, 'updateRecord']);
Route::get('/bid-list/{record_id}', [TransactionController::class, 'bidList']);
Route::post('/accept/bid', [TransactionController::class, 'acceptBid']);

Route::post('/report', [ReportController::class, 'report']);

Route::get('/notifications', [NotificationController::class, 'userNotifications']);

Route::post('bid', [BidController::class, 'store']);

Route::post('getAllBids', [BidController::class, 'allBids']);

Route::post('startBid', [BidController::class, 'startBid']);

Route::post('endBid', [BidController::class, 'endBid']);

Route::post('getBiddingStatus', [BidController::class, 'getBiddingStatus']);

Route::post('like', [LikesController::class, 'store']);

Route::get('like', [LikesController::class, 'index'])->name('likes');

Route::post('unlike', [LikesController::class, 'unlike'])->name('unlike');

Route::post('follow', [FollowController::class, 'follow']);

Route::post('unfollow', [FollowController::class, 'unfollow']);

Route::post('addNotification', [NotificationController::class, 'addNotification']);

Route::get('followers', [FollowController::class, 'index'])->name('followers');
