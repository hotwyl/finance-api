<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\WalletController;

Route::get('/ping', function () { return ['pong' => true]; });

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum');
    Route::get('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
    Route::get('/unlogged', [AuthController::class, 'unlogged'])->name('unlogged');
    Route::get('/me', [AuthController::class, 'me'])->middleware('auth:sanctum');
    Route::post('/update-user', [AuthController::class, 'updateUser'])->middleware('auth:sanctum');
    Route::get('/refresh', [AuthController::class, 'refresh'])->middleware('auth:sanctum');
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);
    Route::post('/update-password', [AuthController::class, 'updatePassword'])->middleware('auth:sanctum');
});

Route::prefix('/')->middleware('auth:sanctum')->group(function () {
    Route::apiResource('users', UserController::class);
    Route::apiResource('wallets', WalletController::class);
    Route::apiResource('wallets.transactions', TransactionController::class);
});
