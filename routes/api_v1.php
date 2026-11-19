<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\ShoeController;
use App\Http\Controllers\Api\V1\UserShoesController;
use Illuminate\Support\Facades\Route;

// Public routes: no token needed
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
});

// Protected routes: the auth:api middleware rejects requests without a valid token (401)
Route::middleware('auth:api')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::get('me', [AuthController::class, 'me']);
        Route::post('refresh', [AuthController::class, 'refresh']);
    });

    // GET /shoes (list) and GET /shoes/{shoe} (one shoe)
    Route::apiResource('shoes', ShoeController::class)->only(['index', 'show']);

    // Authors are read only: list and show
    Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

    // Shoes of one user: GET /users/{user}/shoes
    Route::apiResource('users.shoes', UserShoesController::class)->only(['index']);
});
