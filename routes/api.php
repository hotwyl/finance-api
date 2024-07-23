<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'issueToken'])->middleware('api');
Route::post('/register', [AuthController::class, 'store']);
Route::get('/user', [AuthController::class, 'user'])->middleware('auth:api');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::apiResource('users', UserController::class);
});

//Route::get('/user', function (Request $request) {
//    return $request->user();
//})->middleware('auth:sanctum');

Route::apiResource('wallets', WalletController::class)->middleware('auth:api');

Route::apiResource('wallets.transactions', TransactionController::class)->middleware('auth:api');
