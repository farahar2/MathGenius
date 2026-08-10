<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Niveau;
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
        $niveau = Niveau::factory()->create();

        $response = $this->postJson('/api/chapitres', [
            'titre'     => 'Chapitre 1',
            'id_niveau' => $niveau->id,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('data.id_niveau', $niveau->id);
    }
}
