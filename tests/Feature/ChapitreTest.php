<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Niveau;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ChapitreTest extends TestCase
{
    use RefreshDatabase;

    public function test_factory_creates_chapitre_with_niveau(): void
    {
        $chapitre = Chapitre::factory()->create();

        $this->assertDatabaseHas('chapitres', ['id' => $chapitre->id]);
        $this->assertNotNull($chapitre->id_niveau);
        $this->assertInstanceOf(Niveau::class, $chapitre->niveau);
    }

    public function test_store_chapitre_returns_201_with_valid_id_niveau(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $niveau = Niveau::factory()->create();

        $response = $this->actingAs($formateur)->postJson('/api/chapitres', [
            'titre'     => 'Chapitre 1',
            'id_niveau' => $niveau->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.id_niveau', $niveau->id);
    }

    public function test_store_chapitre_returns_422_with_invalid_data(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);

        $response = $this->actingAs($formateur)->postJson('/api/chapitres', [
            'titre'     => '',
            'id_niveau' => 999,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['titre', 'id_niveau']);
    }

    public function test_update_chapitre_returns_200_with_valid_data(): void
    {
        $formateur = User::factory()->create(['role' => 'formateur']);
        $chapitre = Chapitre::factory()->create();
        $niveau = Niveau::factory()->create();

        $response = $this->actingAs($formateur)->putJson("/api/chapitres/{$chapitre->id}", [
            'titre'     => 'Chapitre mis à jour',
            'id_niveau' => $niveau->id,
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('data.titre', 'Chapitre mis à jour')
            ->assertJsonPath('data.id_niveau', $niveau->id);
    }
}
