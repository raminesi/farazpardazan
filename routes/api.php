<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\MarketController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
Route::post('register', [AuthController::class, 'register'])->name('register');
Route::post('login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:api')->group(function () {
    Route::get('warehouse', [MarketController::class, 'warehouse'])->name('warehouse');
    Route::get('wallet', [MarketController::class, 'wallet'])->name('wallet');
    Route::post('order', [MarketController::class, 'order'])->name('order');
    Route::put('order/{order_id}', [MarketController::class, 'order_confirm'])->name('order_confirm');
    Route::put('order/cancell/{order_id}', [MarketController::class, 'order_cancell'])->name('order_cancell');
});
