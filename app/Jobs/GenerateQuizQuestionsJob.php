<?php

namespace App\Jobs;

use App\Ai\Agents\QuizGeneratorAgent;
use App\Models\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Throwable;

class GenerateQuizQuestionsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        public readonly Quiz $quiz,
        public readonly int $nombreQuestions = 5,
    ) {}

    public function handle(QuizGeneratorAgent $agent): void
    {
        $lecon = $this->quiz->lecon;
        $niveau = $this->quiz->niveau ?? 'non précisé';

        $response = $agent->prompt(<<<PROMPT
            Leçon : {$lecon->titre}

            Contenu de la leçon :
            {$lecon->contenu}

            Difficulté : {$this->quiz->difficulte}
            Niveau : {$niveau}
            Nombre de questions à générer : {$this->nombreQuestions}
            PROMPT);

        collect($response['questions'])
            ->take($this->nombreQuestions)
            ->values()
            ->each(function (array $question, int $index): void {
                $this->quiz->questions()->create([
                    'question' => $question['question'],
                    'option_a' => $question['option_a'],
                    'option_b' => $question['option_b'],
                    'option_c' => $question['option_c'],
                    'option_d' => $question['option_d'],
                    'bonne_reponse' => $question['bonne_reponse'],
                    'explication' => $question['explication'] ?? null,
                    'notion' => $question['notion'] ?? null,
                    'ordre' => $index + 1,
                ]);
            });
    }

    public function failed(Throwable $exception): void
    {
        Log::error('Échec de la génération IA des questions du quiz', [
            'quiz_id' => $this->quiz->id,
            'message' => $exception->getMessage(),
        ]);
    }
}
