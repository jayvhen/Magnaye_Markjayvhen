<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\AuthorsController;
use App\Http\Controllers\Api\V1\ShoeController;
use App\Http\Controllers\Api\V1\UserController;
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
    Route::apiResource('shoes', ShoeController::class)->only(['index', 'show', 'store', 'destroy']);
    // PUT replaces the whole shoe (our own method name: replace)
    Route::put('shoes/{shoe}', [ShoeController::class, 'replace']);
    // PATCH updates only the fields that were sent
    Route::patch('shoes/{shoe}', [ShoeController::class, 'update']);

    // Authors are read only: list and show
    Route::apiResource('authors', AuthorsController::class)->only(['index', 'show']);

    // Users (management): list, create, show and delete use the resource routes
    Route::apiResource('users', UserController::class)->except(['update']);
    Route::put('users/{user}', [UserController::class, 'replace']);
    Route::patch('users/{user}', [UserController::class, 'update']);

    // Shoes of one user: GET /users/{user}/shoes
    Route::apiResource('users.shoes', UserShoesController::class)
        ->only(['index', 'store', 'destroy'])
        ->scoped(); // the shoe must belong to the user in the URL (otherwise 404)

    // PUT and PATCH use our own method names, so they are registered by hand
    Route::put('users/{user}/shoes/{shoe}', [UserShoesController::class, 'replace'])
        ->scopeBindings();
    Route::patch('users/{user}/shoes/{shoe}', [UserShoesController::class, 'update'])
        ->scopeBindings();
});
