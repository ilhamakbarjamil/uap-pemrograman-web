<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PerfumeController;
use App\Http\Controllers\TransactionController;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth Routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('auth:api')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });
});

// Perfume Routes
Route::prefix('perfumes')->group(function () {
    // Public routes (tanpa middleware)
    Route::get('/', [PerfumeController::class, 'index']);
    Route::get('/{id}', [PerfumeController::class, 'show']);
    
    // Protected routes (dengan middleware auth - admin only untuk CRUD)
    Route::middleware('auth:api')->group(function () {
        Route::post('/', [PerfumeController::class, 'store']);
        Route::match(['patch', 'post'], '/{id}', [PerfumeController::class, 'update']); // Allow POST with _method=PATCH for FormData
        Route::delete('/{id}', [PerfumeController::class, 'destroy']);
    });
});

// Transaction Routes (protected - requires authentication)
Route::prefix('transactions')->middleware('auth:api')->group(function () {
    Route::get('/', [TransactionController::class, 'index']);
    Route::post('/', [TransactionController::class, 'store']); // Checkout
    Route::get('/{id}', [TransactionController::class, 'show']);
});