<?php

namespace Database\Factories;

use App\Models\Exercice;
use App\Models\Lecon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Exercice>
 */
class ExerciceFactory extends Factory
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
            'enonce' => $this->faker->paragraph(),
            'correction' => $this->faker->paragraph(),
            'ordre' => $this->faker->numberBetween(1, 10),
            'is_published' => true,
            'id_lecon' => Lecon::factory(),
        ];
    }

    /**
     * Exercice en brouillon, invisible pour les élèves et les invités.
     */
    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
