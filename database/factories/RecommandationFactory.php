<?php

namespace Database\Factories;

use App\Models\Chapitre;
use App\Models\Recommandation;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Recommandation>
 */
class RecommandationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'message' => $this->faker->sentence(),
            'is_lue' => false,
            'id_chapitre' => Chapitre::factory(),
            'id_utilisateur' => User::factory(),
        ];
    }
}
