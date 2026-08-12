<?php

namespace Database\Factories;

use App\Models\Chapitre;
use App\Models\Lecon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Lecon>
 */
class LeconFactory extends Factory
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
            'contenu' => $this->faker->paragraphs(3, true),
            'ordre' => $this->faker->numberBetween(1, 10),
            'is_published' => true,
            'id_chapitre' => Chapitre::factory(),
        ];
    }

    /**
     * Leçon en brouillon, invisible pour les élèves et les invités.
     */
    public function unpublished(): static
    {
        return $this->state(fn () => ['is_published' => false]);
    }
}
