<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeconTest extends TestCase
{
    use RefreshDatabase;

    public function test_formateur_can_create_lecon(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $chapitre = Chapitre::factory()->create();

        $response = $this->actingAs($formateur)->postJson('/api/lecons', [
            'titre'       => 'Leçon 1',
            'contenu'     => 'Contenu de la leçon',
            'id_chapitre' => $chapitre->id,
        ]);

        $response->assertStatus(201);
    }

    public function test_student_cannot_create_lecon(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $chapitre = Chapitre::factory()->create();

        $response = $this->actingAs($student)->postJson('/api/lecons', [
            'titre'       => 'Leçon 1',
            'contenu'     => 'Contenu de la leçon',
            'id_chapitre' => $chapitre->id,
        ]);

        $response->assertStatus(403);
    }
}
