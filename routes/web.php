<?php

use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingController::class, 'landing'])->name('landing');
Route::get('/login', [MarketingController::class, 'showLogin'])->name('login');
Route::get('/register', [MarketingController::class, 'showRegister'])->name('register');
Route::get('/app/dashboard', [MarketingController::class, 'dashboard'])->name('app.dashboard');
