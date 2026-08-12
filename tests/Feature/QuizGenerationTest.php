<?php

namespace Tests\Feature;

use App\Ai\Agents\QuizGeneratorAgent;
use App\Jobs\GenerateQuizQuestionsJob;
use App\Models\Chapitre;
use App\Models\Lecon;
use App\Models\Niveau;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QuizGenerationTest extends TestCase
{
    use RefreshDatabase;

    private function makeLecon(): Lecon
    {
        $niveau = Niveau::factory()->create();
        $chapitre = Chapitre::factory()->create(['id_niveau' => $niveau->id]);

        return Lecon::create([
            'titre' => 'Les limites',
            'contenu' => 'Une limite décrit le comportement d\'une fonction...',
            'id_chapitre' => $chapitre->id,
        ]);
    }

    public function test_guest_cannot_generate_quiz(): void
    {
        $lecon = $this->makeLecon();

        $this->postJson('/api/quiz/generate', ['id_lecon' => $lecon->id])
            ->assertStatus(401);
    }

    public function test_generate_quiz_requires_an_existing_lecon(): void
    {
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)->postJson('/api/quiz/generate', ['id_lecon' => 999])
            ->assertStatus(422);
    }

    public function test_student_can_generate_quiz(): void
    {
        Queue::fake();

        $student = User::factory()->create(['role' => 'student']);
        $lecon = $this->makeLecon();

        $response = $this->actingAs($student)->postJson('/api/quiz/generate', [
            'id_lecon' => $lecon->id,
            'difficulte' => 'facile',
            'nombre_questions' => 3,
        ])->assertStatus(202);

        $quizId = $response->json('data.id');

        $this->assertDatabaseHas('quiz', [
            'id' => $quizId,
            'id_lecon' => $lecon->id,
            'id_chapitre' => $lecon->id_chapitre,
            'difficulte' => 'facile',
        ]);

        Queue::assertPushed(GenerateQuizQuestionsJob::class, function (GenerateQuizQuestionsJob $job) use ($quizId) {
            return $job->quiz->id === $quizId && $job->nombreQuestions === 3;
        });
    }

    public function test_generate_quiz_job_creates_questions_from_ai_response(): void
    {
        QuizGeneratorAgent::fake([
            [
                'questions' => [
                    [
                        'question' => 'Combien font 2 + 2 ?',
                        'option_a' => '3',
                        'option_b' => '4',
                        'option_c' => '5',
                        'option_d' => '6',
                        'bonne_reponse' => 'B',
                        'explication' => '2 + 2 = 4',
                        'notion' => 'Addition',
                    ],
                ],
            ],
        ]);

        $lecon = $this->makeLecon();
        $quiz = Quiz::create([
            'id_lecon' => $lecon->id,
            'id_chapitre' => $lecon->id_chapitre,
            'difficulte' => 'facile',
        ]);

        (new GenerateQuizQuestionsJob($quiz, 1))->handle(new QuizGeneratorAgent);

        $this->assertDatabaseHas('questions', [
            'id_quiz' => $quiz->id,
            'question' => 'Combien font 2 + 2 ?',
            'bonne_reponse' => 'B',
            'notion' => 'Addition',
            'ordre' => 1,
        ]);
    }
}
