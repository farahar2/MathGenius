<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\MarketingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingController::class, 'landing'])->name('landing');
Route::get('/login', [MarketingController::class, 'showLogin'])->name('login');
Route::get('/register', [MarketingController::class, 'showRegister'])->name('register');

Route::prefix('app')->name('app.')->group(function () {
    Route::get('/dashboard', [AppController::class, 'dashboard'])->name('dashboard');
    Route::get('/chapters', [AppController::class, 'chapters'])->name('chapters');
    Route::get('/chapters/{chapitre}', [AppController::class, 'chapterShow'])->name('chapters.show');
    Route::get('/quiz', [AppController::class, 'quizSetup'])->name('quiz.setup');
    Route::get('/quiz/{quiz}/jouer', [AppController::class, 'quizPlay'])->name('quiz.play');
    Route::get('/resultats', [AppController::class, 'results'])->name('results');
    Route::get('/resultats/{tentative}', [AppController::class, 'resultShow'])->name('results.show');
    Route::get('/profil', [AppController::class, 'profile'])->name('profile');
    Route::get('/admin', [AppController::class, 'admin'])->name('admin');
});

