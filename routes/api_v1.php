<?php

use App\Http\Controllers\Api\V1\ShoeController;
use Illuminate\Support\Facades\Route;

// apiResource creates the standard REST routes; only() keeps just the ones we need now.
// GET /api/v1/shoes -> ShoeController@index
Route::apiResource('shoes', ShoeController::class)->only(['index']);
