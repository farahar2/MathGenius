<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Exercice;
use App\Models\Lecon;
use App\Models\Niveau;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;

class ResourceStructureTest extends TestCase
{
    use RefreshDatabase;

    private function assertExactDataKeys(TestResponse $response, array $expectedKeys): void
    {
        $this->assertEqualsCanonicalizing($expectedKeys, array_keys($response->json('data')));
    }

    private function makeLecon(): Lecon
    {
        $niveau = Niveau::factory()->create();
        $chapitre = Chapitre::factory()->create(['id_niveau' => $niveau->id]);

        return Lecon::create([
            'titre' => 'Leçon 1',
            'contenu' => 'Contenu',
            'id_chapitre' => $chapitre->id,
        ]);
    }

    private function makeQuizWithQuestion(): Quiz
    {
        $lecon = $this->makeLecon();
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

    public function test_niveau_show_exposes_only_expected_fields(): void
    {
        $niveau = Niveau::factory()->create();

        $response = $this->getJson("/api/niveaux/{$niveau->id}")->assertStatus(200);

        $this->assertExactDataKeys($response, ['id', 'nom', 'ordre', 'chapitres', 'created_at', 'updated_at']);
    }

    public function test_exercice_show_exposes_only_expected_fields(): void
    {
        $lecon = $this->makeLecon();
        $exercice = Exercice::create([
            'titre' => 'Exo',
            'enonce' => 'Énoncé',
            'correction' => 'Correction',
            'id_lecon' => $lecon->id,
        ]);

        $response = $this->getJson("/api/exercices/{$exercice->id}")->assertStatus(200);

        $this->assertExactDataKeys($response, [
            'id', 'titre', 'enonce', 'correction', 'image', 'fichier_pdf',
            'ordre', 'is_published', 'id_lecon', 'lecon', 'created_at', 'updated_at',
        ]);
    }

    public function test_question_show_exposes_only_expected_fields(): void
    {
        $quiz = $this->makeQuizWithQuestion();
        $question = $quiz->questions()->first();

        $response = $this->getJson("/api/questions/{$question->id}")->assertStatus(200);

        $this->assertExactDataKeys($response, [
            'id', 'question', 'option_a', 'option_b', 'option_c', 'option_d',
            'bonne_reponse', 'explication', 'notion', 'ordre', 'id_quiz', 'quiz',
            'created_at', 'updated_at',
        ]);
    }

    public function test_quiz_show_exposes_only_expected_fields(): void
    {
        $quiz = $this->makeQuizWithQuestion();

        $response = $this->getJson("/api/quiz/{$quiz->id}")->assertStatus(200);

        $this->assertExactDataKeys($response, [
            'id', 'id_lecon', 'id_chapitre', 'difficulte', 'niveau', 'duree_secondes',
            'lecon', 'questions', 'created_at', 'updated_at',
        ]);
    }

    public function test_tentative_show_exposes_only_expected_fields(): void
    {
        $user = User::factory()->create();
        $quiz = $this->makeQuizWithQuestion();
        $question = $quiz->questions()->first();

        $store = $this->actingAs($user)->postJson('/api/tentatives', [
            'id_quiz' => $quiz->id,
            'reponses' => [
                ['id_question' => $question->id, 'reponse_eleve' => 'A'],
            ],
        ])->assertStatus(201);

        $tentativeId = $store->json('data.id');

        $response = $this->actingAs($user)->getJson("/api/tentatives/{$tentativeId}")->assertStatus(200);

        $this->assertExactDataKeys($response, [
            'id', 'score', 'score_pct', 'analyse_ia', 'recomm_ia', 'completed_at',
            'id_quiz', 'id_utilisateur', 'quiz', 'reponses', 'created_at', 'updated_at',
        ]);
    }

    public function test_recommandation_update_exposes_only_expected_fields(): void
    {
        $user = User::factory()->create();
        $niveau = Niveau::factory()->create();
        $chapitre = Chapitre::factory()->create(['id_niveau' => $niveau->id]);
        $recommandation = Recommandation::create([
            'message' => 'Travaille les fractions',
            'id_chapitre' => $chapitre->id,
            'id_utilisateur' => $user->id,
        ]);

        $response = $this->actingAs($user)
            ->putJson("/api/recommandations/{$recommandation->id}", ['is_lue' => true])
            ->assertStatus(200);

        $this->assertExactDataKeys($response, ['id', 'message', 'is_lue', 'id_chapitre', 'id_utilisateur', 'created_at', 'updated_at']);
    }
}
