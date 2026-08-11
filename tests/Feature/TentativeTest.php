<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Lecon;
use App\Models\Niveau;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Tentative;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TentativeTest extends TestCase
{
    use RefreshDatabase;

    private function makeQuizWithQuestion(): Quiz
    {
        $niveau = Niveau::factory()->create();
        $chapitre = Chapitre::factory()->create(['id_niveau' => $niveau->id]);
        $lecon = Lecon::create([
            'titre' => 'Leçon 1',
            'contenu' => 'Contenu',
            'id_chapitre' => $chapitre->id,
        ]);
        $quiz = Quiz::create([
            'id_lecon' => $lecon->id,
            'difficulte' => 'moyen',
            'duree_secondes' => 300,
        ]);
        Question::create([
            'question' => 'Q ?',
            'option_a' => 'A',
            'option_b' => 'B',
            'option_c' => 'C',
            'option_d' => 'D',
            'bonne_reponse' => 'A',
            'id_quiz' => $quiz->id,
        ]);

        return $quiz;
    }

    private function makeTentativeFor(User $user): Tentative
    {
        $quiz = $this->makeQuizWithQuestion();
        $question = $quiz->questions()->first();

        $response = $this->actingAs($user)->postJson('/api/tentatives', [
            'id_quiz' => $quiz->id,
            'reponses' => [
                ['id_question' => $question->id, 'reponse_eleve' => 'A'],
            ],
        ])->assertStatus(201);

        return Tentative::findOrFail($response->json('data.id'));
    }

    public function test_owner_can_view_own_tentative(): void
    {
        $userA = User::factory()->create();
        $tentative = $this->makeTentativeFor($userA);

        $this->actingAs($userA)->getJson("/api/tentatives/{$tentative->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $tentative->id);
    }

    public function test_other_authenticated_user_cannot_view_tentative(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $tentative = $this->makeTentativeFor($userA);

        $this->actingAs($userB)->getJson("/api/tentatives/{$tentative->id}")
            ->assertStatus(403);
    }

    public function test_other_authenticated_user_cannot_delete_tentative(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $tentative = $this->makeTentativeFor($userA);

        $this->actingAs($userB)->deleteJson("/api/tentatives/{$tentative->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('tentatives', ['id' => $tentative->id]);
    }

    public function test_owner_can_delete_own_tentative(): void
    {
        $userA = User::factory()->create();
        $tentative = $this->makeTentativeFor($userA);

        $this->actingAs($userA)->deleteJson("/api/tentatives/{$tentative->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('tentatives', ['id' => $tentative->id]);
    }

    public function test_guest_cannot_view_tentative(): void
    {
        $userA = User::factory()->create();
        $quiz = $this->makeQuizWithQuestion();
        $tentative = Tentative::create([
            'score' => 1,
            'score_pct' => 100.00,
            'completed_at' => now(),
            'id_quiz' => $quiz->id,
            'id_utilisateur' => $userA->id,
        ]);

        $this->getJson("/api/tentatives/{$tentative->id}")
            ->assertStatus(401);
    }

    public function test_guest_cannot_store_tentative(): void
    {
        $quiz = $this->makeQuizWithQuestion();
        $question = $quiz->questions()->first();

        $this->postJson('/api/tentatives', [
            'id_quiz' => $quiz->id,
            'reponses' => [
                ['id_question' => $question->id, 'reponse_eleve' => 'A'],
            ],
        ])->assertStatus(401);
    }

    public function test_store_tentative_returns_422_with_invalid_data(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->postJson('/api/tentatives', [
            'id_quiz' => 999,
            'reponses' => 'not-an-array',
        ])->assertStatus(422)
            ->assertJsonValidationErrors(['id_quiz', 'reponses']);
    }

    public function test_store_tentative_computes_score_from_answers(): void
    {
        $user = User::factory()->create();
        $quiz = $this->makeQuizWithQuestion();
        $question = $quiz->questions()->first();

        $response = $this->actingAs($user)->postJson('/api/tentatives', [
            'id_quiz' => $quiz->id,
            'reponses' => [
                ['id_question' => $question->id, 'reponse_eleve' => 'B'],
            ],
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.score', 0)
            ->assertJsonPath('data.score_pct', 0);
    }
}
