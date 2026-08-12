<?php

namespace App\Services;

use App\Jobs\GenerateQuizQuestionsJob;
use App\Models\Lecon;
use App\Models\Quiz;

class QuizService
{
    /**
     * Create the quiz shell and dispatch the AI question generation job.
     */
    public function generateAndDispatch(Lecon $lecon, array $options): Quiz
    {
        $quiz = Quiz::create([
            'id_lecon' => $lecon->id,
            'id_chapitre' => $lecon->id_chapitre,
            'difficulte' => $options['difficulte'] ?? 'moyen',
            'niveau' => $options['niveau'] ?? null,
            'duree_secondes' => $options['duree_secondes'] ?? 0,
        ]);

        GenerateQuizQuestionsJob::dispatch($quiz, $options['nombre_questions'] ?? 5)
            ->onQueue('ai');

        return $quiz;
    }
}
