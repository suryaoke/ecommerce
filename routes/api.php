<?php

use App\Http\Controllers\StoreBallanceController;
use App\Http\Controllers\StoreBallanceHistoryController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use Illuminate\Support\Facades\Route;

Route::apiResource('user', UserController::class);

Route::get('user/all/paginated', [UserController::class, 'getAllPaginated']);

Route::apiResource('store', StoreController::class);
Route::get('store/all/paginated', [StoreController::class, 'getAllPaginated']);
Route::post('store/{id}', [StoreController::class, 'update']); 
Route::put('store/{id}/verified', [StoreController::class, 'updateVerifiedStatus']);

Route::apiResource('store-ballance', StoreBallanceController::class)->except(['store','update','delete']);

Route::get('store-ballance/all/paginated', [StoreBallanceController::class, 'getAllPaginated']);


Route::apiResource('store-balance-history', StoreBallanceHistoryController::class)->except(['store','update','delete']);
Route::get('store-balance-history/all/paginated', [StoreBallanceHistoryController::class, 'getAllPaginated']);

Route::apiResource('withdrawal', WithdrawalController::class)->except(['update','delete']);

Route::get('withdrawal/all/paginated', [WithdrawalController::class, 'getAllPaginated']);


Route::put('withdrawal/{id}/approve', [WithdrawalController::class, 'approve']);