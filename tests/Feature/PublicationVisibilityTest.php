<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Exercice;
use App\Models\Lecon;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Le drapeau `is_published` existait sur chapitres/lecons/exercices mais
 * n'était jamais consulté : les brouillons étaient servis par l'API.
 */
class PublicationVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_unpublished_chapitre_is_hidden_from_guests(): void
    {
        $brouillon = Chapitre::factory()->unpublished()->create();

        $this->getJson("/api/chapitres/{$brouillon->id}")->assertStatus(404);

        $this->getJson('/api/chapitres')
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }

    public function test_unpublished_chapitre_is_visible_to_formateur(): void
    {
        $brouillon = Chapitre::factory()->unpublished()->create();
        $formateur = User::factory()->create(['role' => 'formateur']);

        $this->actingAs($formateur)
            ->getJson("/api/chapitres/{$brouillon->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $brouillon->id);
    }

    public function test_published_chapitre_stays_public(): void
    {
        $chapitre = Chapitre::factory()->create();

        $this->getJson("/api/chapitres/{$chapitre->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $chapitre->id);
    }

    public function test_unpublished_lecon_is_hidden_from_students(): void
    {
        $brouillon = Lecon::factory()->unpublished()->create();
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->getJson("/api/lecons/{$brouillon->id}")
            ->assertStatus(404);
    }

    public function test_unpublished_exercice_is_hidden_from_students(): void
    {
        $brouillon = Exercice::factory()->unpublished()->create();
        $student = User::factory()->create(['role' => 'student']);

        $this->actingAs($student)
            ->getJson("/api/exercices/{$brouillon->id}")
            ->assertStatus(404);

        $this->actingAs($student)
            ->getJson("/api/lecons/{$brouillon->id_lecon}/exercices")
            ->assertStatus(200)
            ->assertJsonCount(0, 'data');
    }
}
