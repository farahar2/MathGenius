<?php

namespace Database\Factories;

use App\Models\Chapitre;
use App\Models\Niveau;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Chapitre>
 */
class ChapitreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titre' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'ordre' => $this->faker->numberBetween(1, 10),
            'is_published' => $this->faker->boolean(),
            'id_niveau' => Niveau::factory(),
        ];
    }
}
