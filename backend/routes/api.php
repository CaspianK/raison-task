<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::post('/signup', [AuthController::class, 'signup']);
Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/purchases', [PurchaseController::class, 'index']);
    Route::post('/purchases', [PurchaseController::class, 'store']);
    Route::put('/purchases/{purchase}', [PurchaseController::class, 'update']);

    Route::resource('purchases', PurchaseController::class)->except(['show']);

    Route::get('/currencies', [CurrencyController::class, 'index']);
    Route::get('/currencies/exchange', [CurrencyController::class, 'exchange']);

    Route::get('/stores', [StoreController::class, 'index']);

    Route::get('/search', [StoreController::class, 'search']);
});
