<?php

namespace App\Actions\Quiz;

use App\Models\Lecon;
use App\Models\Quiz;
use App\Services\QuizService;

class GenerateQuiz
{
    public function __construct(
        private readonly QuizService $quizService,
    ) {}

    public function __invoke(Lecon $lecon, array $options): Quiz
    {
        return $this->quizService->generateAndDispatch($lecon, $options);
    }
}
