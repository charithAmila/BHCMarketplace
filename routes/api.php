<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Api\ShortUrlController;
use App\Http\Controllers\Api\BidController;
use App\Http\Controllers\Api\ExposeDataController;
use App\Http\Controllers\CollectionsController;
use App\Http\Controllers\SalesController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/keygen', [PinataController::class, 'generateJWT']);

Route::resource('/profile', ProfileController::class);
Route::apiResource('shorturls', ShortUrlController::class);
Route::resource('collections', CollectionsController::class);
Route::get('get-nft-table', [ExposeDataController::class, 'index']);
