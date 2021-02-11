<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('homepage');
});
Route::get('/nft', [App\Http\Controllers\IndexController::class, 'index'])->name('marketplace');

Route::get('/connect', [App\Http\Controllers\WalletController::class, 'index'])->name('connect.wallet');

Route::get('/faq', [IndexController::class, 'faq'])->name('faq');

Route::get('/bhc', [IndexController::class, 'about'])->name('about');

Route::get('/create', [CollectibleController::class, 'index'])->name('create.collectible.choices');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');