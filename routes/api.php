<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PinataController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CollectionsController;

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

Route::resource('profile', ProfileController::class);
Route::resource('collections', CollectionsController::class);
