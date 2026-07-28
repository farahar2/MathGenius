<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| MathGenius API routes. Protected by Laravel Sanctum.
|
*/

Route::get('/user', function () {
    return response()->json(['message' => 'MathGenius API is running']);
})->middleware('auth:sanctum');
