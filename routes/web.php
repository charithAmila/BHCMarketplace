<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CollectibleController;
use App\Http\Controllers\CollectionController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\TransactionController;

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
Route::get('/nft/{user_slug}/{slug}', [IndexController::class, 'show'])->name('show.collectible');
Route::get('/update/show-page/{user_slug}/{slug}', [IndexController::class, 'fetchShowNft']);



Route::get('/nft/fetch/{slug}', [IndexController::class, 'fetch']);
Route::get('/nft/filter/{category}', [IndexController::class, 'filterCategory']);
Route::get('/nft/user/filter', [IndexController::class, 'filterUser']);
Route::get('/bhc', [IndexController::class, 'about'])->name('about');
Route::get('/faq', [IndexController::class, 'faq'])->name('faq');

Route::get('/create', [CollectibleController::class, 'index'])->name('create.collectible.choices');
Route::get('/create/{type}', [CollectibleController::class, 'create']);
Route::post('/create/collectible', [CollectibleController::class, 'store'])->name('create.collectible');



Route::get('/connect', [WalletController::class, 'index'])->name('connect.wallet');
Route::get('/wallet/fetch', [WalletController::class, 'fetch'])->name('wallet.fetch');

Route::middleware('signed')->prefix('/connect/{user_id}')->group(function () {
    Route::get('/', [WalletController::class, 'customLogin'])->name('custom.login');
});
Route::get('/profile/{slug}', [UserController::class, 'index'])->name('user.profile');
Route::get('/profile/nft/fetch/{user_slug}/{filter?}', [UserController::class, 'getUserNft']);
Route::get('users/create', [UserController::class, 'create'])->name('create.user');
Route::get('/user/fetch', [UserController::class, 'fetch']);
Route::get('/users/{filter}/{slug}', [UserController::class, 'filter']);
Route::post('/users/update', [UserController::class, 'update'])->name('user.update');
Route::post('/follower/{short_url}', [UserController::class, 'follower']);
Route::get('/user-follow/{short_url}/{filter}', [UserController::class, 'getFollowing']);



Route::post('/collection', [CollectionController::class, 'store'])->name('create.collection');

Route::post('/wishlist/{slug}', [LikeController::class, 'wishlist']);



Route::get('/logout', function(){
	Auth::logout();
	return redirect()->back();
})->name('disconnect');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/create/transaction', [App\Http\Controllers\TransactionController::class, 'store']);
Route::get('/update/nft/status/{slug}/{user_id}', [App\Http\Controllers\TransactionController::class, 'updateRecord']);
