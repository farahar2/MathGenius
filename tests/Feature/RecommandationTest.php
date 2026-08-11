<?php

namespace Tests\Feature;

use App\Models\Chapitre;
use App\Models\Niveau;
use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecommandationTest extends TestCase
{
    use RefreshDatabase;

    private function makeRecommandationFor(User $user): Recommandation
    {
        $niveau = Niveau::factory()->create();
        $chapitre = Chapitre::factory()->create(['id_niveau' => $niveau->id]);

        return Recommandation::create([
            'message' => 'Travaille les fractions',
            'id_chapitre' => $chapitre->id,
            'id_utilisateur' => $user->id,
        ]);
    }

    public function test_owner_can_update_own_recommandation(): void
    {
        $userA = User::factory()->create();
        $recommandation = $this->makeRecommandationFor($userA);

        $this->actingAs($userA)->putJson("/api/recommandations/{$recommandation->id}", ['is_lue' => true])
            ->assertStatus(200)
            ->assertJsonPath('data.is_lue', true);
    }

    public function test_other_authenticated_user_cannot_update_recommandation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $recommandation = $this->makeRecommandationFor($userA);

        $this->actingAs($userB)->putJson("/api/recommandations/{$recommandation->id}", ['is_lue' => true])
            ->assertStatus(403);

        $this->assertDatabaseHas('recommandations', ['id' => $recommandation->id, 'is_lue' => false]);
    }

    public function test_owner_can_delete_own_recommandation(): void
    {
        $userA = User::factory()->create();
        $recommandation = $this->makeRecommandationFor($userA);

        $this->actingAs($userA)->deleteJson("/api/recommandations/{$recommandation->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('recommandations', ['id' => $recommandation->id]);
    }

    public function test_other_authenticated_user_cannot_delete_recommandation(): void
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $recommandation = $this->makeRecommandationFor($userA);

        $this->actingAs($userB)->deleteJson("/api/recommandations/{$recommandation->id}")
            ->assertStatus(403);

        $this->assertDatabaseHas('recommandations', ['id' => $recommandation->id]);
    }
}
