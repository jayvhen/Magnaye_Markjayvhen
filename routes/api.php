<?php

use App\Models\Shoe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// TEMPORARY: delete in Step 7. Returns every shoe so we can see the seeded data.
Route::get('/shoes', function () {
    return Shoe::all();
});
