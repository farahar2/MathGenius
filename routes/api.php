<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChapitreController;
use App\Http\Controllers\ExerciceController;
use App\Http\Controllers\LeconController;
use App\Http\Controllers\NiveauController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RecommandationController;
use App\Http\Controllers\TentativeController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:register');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('niveaux', NiveauController::class)->except(['index', 'show']);
    Route::apiResource('chapitres', ChapitreController::class)->except(['index', 'show']);
    Route::apiResource('quiz', QuizController::class)->except(['index', 'show']);
    Route::apiResource('exercices', ExerciceController::class)->except(['index', 'show']);
    Route::apiResource('questions', QuestionController::class)->except(['index', 'show']);
    Route::apiResource('lecons', LeconController::class)->except(['index']);
});

Route::get('niveaux', [NiveauController::class, 'index']);
Route::get('niveaux/{niveau}', [NiveauController::class, 'show']);

Route::get('chapitres', [ChapitreController::class, 'index']);
Route::get('chapitres/{chapitre}', [ChapitreController::class, 'show']);

Route::get('quiz', [QuizController::class, 'index']);
Route::get('quiz/{quiz}', [QuizController::class, 'show']);

Route::get('exercices', [ExerciceController::class, 'index']);
Route::get('exercices/{exercice}', [ExerciceController::class, 'show']);

Route::get('questions', [QuestionController::class, 'index']);
Route::get('questions/{question}', [QuestionController::class, 'show']);

Route::get('lecons', [LeconController::class, 'index']);

Route::get('chapitres/{chapitre}/lecons', [LeconController::class, 'indexByChapitre'])
    ->name('chapitres.lecons.index');

Route::get('lecons/{lecon}/exercices', [ExerciceController::class, 'indexByLecon'])
    ->name('lecons.exercices.index');

Route::get('quiz/{quiz}/questions', [QuestionController::class, 'indexByQuiz'])
    ->name('quiz.questions.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tentatives', TentativeController::class)->except(['update']);
    Route::apiResource('recommandations', RecommandationController::class)->except(['show']);
});
