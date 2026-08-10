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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::apiResource('niveaux', NiveauController::class);

Route::apiResource('chapitres', ChapitreController::class);

Route::get('chapitres/{chapitre}/lecons', [LeconController::class, 'indexByChapitre'])
    ->name('chapitres.lecons.index');

Route::apiResource('lecons', LeconController::class)->except(['index']);

Route::get('lecons/{lecon}/exercices', [ExerciceController::class, 'indexByLecon'])
    ->name('lecons.exercices.index');

Route::apiResource('quiz', QuizController::class);

Route::get('quiz/{quiz}/questions', [QuestionController::class, 'indexByQuiz'])
    ->name('quiz.questions.index');

Route::apiResource('exercices', ExerciceController::class)->except(['index']);
Route::apiResource('questions', QuestionController::class)->except(['index']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tentatives', TentativeController::class)->except(['update']);
    Route::apiResource('recommandations', RecommandationController::class)->except(['show']);
});
