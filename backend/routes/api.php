<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfumeController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'me']);
    Route::post('refresh', [AuthController::class, 'refresh']);
});

// Perfume Routes
Route::prefix('perfumes')->group(function () {
    // Public routes (tanpa middleware)
    Route::get('/', [PerfumeController::class, 'index']);
    Route::get('/{id}', [PerfumeController::class, 'show']);
    
    // Protected routes (dengan middleware auth)
    Route::middleware('auth:api')->group(function () {
        Route::post('/', [PerfumeController::class, 'store']);
        Route::patch('/{id}', [PerfumeController::class, 'update']);
        Route::delete('/{id}', [PerfumeController::class, 'destroy']);
    });
});