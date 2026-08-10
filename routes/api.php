<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\LeconController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::apiResource('chapitres', ChapitreController::class);

Route::get('chapitres/{chapitre}/lecons', [LeconController::class, 'indexByChapitre'])
    ->name('chapitres.lecons.index');

Route::apiResource('lecons', LeconController::class)->except(['index']);
