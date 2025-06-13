<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::post('register', [Api\AuthController::class, 'register']);
Route::post('login', [Api\AuthController::class, 'login']);
Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [Api\AuthController::class, 'logout']);
    Route::get('products', [Api\ProductController::class, 'index']);
    Route::get('customers', [Api\CustomerController::class, 'index']);
    Route::get('/sales', [Api\SaleController::class, 'index']);
    Route::get('/sales/{id}', [Api\SaleController::class, 'show']);
    Route::post('/sales', [Api\SaleController::class, 'store']);
});
