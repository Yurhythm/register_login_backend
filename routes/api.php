<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

Route::post('register', [Api\AuthController::class, 'register']);
Route::post('login', [Api\AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('logout', [Api\AuthController::class, 'logout']);

