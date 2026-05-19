<?php

use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Support\Facades\Route;

Route::apiResource('products', ProductController::class);
Route::post('orders', [OrderController::class, 'store']);
Route::get('dashboard/summary', [DashboardController::class, 'summary']);
Route::delete('dashboard/cache', [DashboardController::class, 'clearCache']);
Route::get('users/{user}/orders/{order}', [OrderController::class, 'show'])->scopeBindings();
