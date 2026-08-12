<?php

namespace App\Http\Controllers;

use Illuminate\View\View;

class AppController extends Controller
{
    public function dashboard(): View
    {
        return view('pages.app.dashboard');
    }

    public function chapters(): View
    {
        return view('pages.app.chapters');
    }

    public function chapterShow(int $chapitre): View
    {
        return view('pages.app.chapter', ['chapitreId' => $chapitre]);
    }

    public function quizSetup(): View
    {
        return view('pages.app.quiz-setup');
    }

    public function quizPlay(int $quiz): View
    {
        return view('pages.app.quiz-play', ['quizId' => $quiz]);
    }

    public function results(): View
    {
        return view('pages.app.results');
    }

    public function resultShow(int $tentative): View
    {
        return view('pages.app.result', ['tentativeId' => $tentative]);
    }

    public function profile(): View
    {
        return view('pages.app.profile');
    }

    public function admin(): View
    {
        return view('pages.app.admin');
    }
}
