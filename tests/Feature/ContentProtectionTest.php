<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Exercice;
use App\Models\Lecon;
use App\Models\Niveau;
use App\Models\Question;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ContentProtectionTest extends TestCase
{
    use RefreshDatabase;

    private function makeLecon(): Lecon
    {
        $niveau = Niveau::factory()->create();
        $chapitre = Chapitre::factory()->create(['id_niveau' => $niveau->id]);

        return Lecon::create([
            'titre'       => 'Leçon 1',
            'contenu'     => 'Contenu',
            'id_chapitre' => $chapitre->id,
        ]);
    }

    private function makeQuiz(): Quiz
    {
        $lecon = $this->makeLecon();

        return Quiz::create([
            'id_lecon'       => $lecon->id,
            'difficulte'     => 'moyen',
            'duree_secondes' => 300,
        ]);
    }

    public function test_guest_cannot_store_quiz(): void
    {
        $this->postJson('/api/quiz', ['id_lecon' => 1])
            ->assertStatus(401);
    }

    public function test_student_cannot_store_quiz(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $lecon = $this->makeLecon();

        $this->actingAs($student)->postJson('/api/quiz', ['id_lecon' => $lecon->id])
            ->assertStatus(403);
    }

    public function test_formateur_can_store_quiz(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $lecon = $this->makeLecon();

        $this->actingAs($formateur)->postJson('/api/quiz', [
            'id_lecon'       => $lecon->id,
            'difficulte'     => 'moyen',
            'duree_secondes' => 300,
        ])->assertStatus(201);
    }

    public function test_student_cannot_update_quiz(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $quiz = $this->makeQuiz();

        $this->actingAs($student)->putJson("/api/quiz/{$quiz->id}", ['difficulte' => 'difficile'])
            ->assertStatus(403);
    }

    public function test_formateur_can_update_quiz(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $quiz = $this->makeQuiz();

        $this->actingAs($formateur)->putJson("/api/quiz/{$quiz->id}", ['difficulte' => 'difficile'])
            ->assertStatus(200)
            ->assertJsonPath('data.difficulte', 'difficile');
    }

    public function test_student_cannot_delete_quiz(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $quiz = $this->makeQuiz();

        $this->actingAs($student)->deleteJson("/api/quiz/{$quiz->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('quiz', ['id' => $quiz->id]);
    }

    public function test_formateur_can_delete_quiz(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $quiz = $this->makeQuiz();

        $this->actingAs($formateur)->deleteJson("/api/quiz/{$quiz->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('quiz', ['id' => $quiz->id]);
    }

    public function test_guest_cannot_store_chapitre(): void
    {
        $this->postJson('/api/chapitres', ['titre' => 'Chapitre'])
            ->assertStatus(401);
    }

    public function test_student_cannot_store_chapitre(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $niveau = Niveau::factory()->create();

        $this->actingAs($student)->postJson('/api/chapitres', [
            'titre'     => 'Chapitre',
            'id_niveau' => $niveau->id,
        ])->assertStatus(403);
    }

    public function test_guest_cannot_store_question(): void
    {
        $this->postJson('/api/questions', ['question' => 'Q'])
            ->assertStatus(401);
    }

    public function test_student_cannot_store_exercice(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $lecon = $this->makeLecon();

        $this->actingAs($student)->postJson('/api/exercices', [
            'titre'     => 'Exo',
            'enonce'    => 'Énoncé',
            'correction' => 'Correction',
            'id_lecon'  => $lecon->id,
        ])->assertStatus(403);
    }

    public function test_formateur_can_store_question(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $quiz = $this->makeQuiz();

        $this->actingAs($formateur)->postJson('/api/questions', [
            'question'      => 'Quelle est la réponse ?',
            'option_a'      => 'A',
            'option_b'      => 'B',
            'option_c'      => 'C',
            'option_d'      => 'D',
            'bonne_reponse' => 'A',
            'id_quiz'       => $quiz->id,
        ])->assertStatus(201);
    }

    public function test_reads_stay_public(): void
    {
        $quiz = $this->makeQuiz();
        $lecon = Lecon::find($quiz->id_lecon);
        $chapitre = $lecon->chapitre;
        $exercice = Exercice::create([
            'titre'        => 'Exo',
            'enonce'       => 'Énoncé',
            'correction'   => 'Correction',
            'is_published' => true,
            'id_lecon'     => $lecon->id,
        ]);
        $question = Question::create([
            'question'      => 'Q ?',
            'option_a'      => 'A',
            'option_b'      => 'B',
            'option_c'      => 'C',
            'option_d'      => 'D',
            'bonne_reponse' => 'A',
            'id_quiz'       => $quiz->id,
        ]);

        $this->getJson('/api/niveaux')->assertStatus(200);
        $this->getJson('/api/niveaux/'.$chapitre->id_niveau)->assertStatus(200);
        $this->getJson('/api/chapitres')->assertStatus(200);
        $this->getJson('/api/chapitres/'.$chapitre->id)->assertStatus(200);
        $this->getJson('/api/quiz')->assertStatus(200);
        $this->getJson('/api/quiz/'.$quiz->id)->assertStatus(200);
        $this->getJson("/api/quiz/{$quiz->id}/questions")->assertStatus(200);
        $this->getJson('/api/exercices/'.$exercice->id)->assertStatus(200);
        $this->getJson('/api/questions/'.$question->id)->assertStatus(200);
    }
}
