<?php

namespace Database\Factories;

use App\Models\Lecon;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Quiz>
 */
class QuizFactory extends Factory
{
    protected $model = Quiz::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'id_lecon' => Lecon::factory(),
            'id_chapitre' => null,
            'difficulte' => $this->faker->randomElement(['facile', 'moyen', 'difficile']),
            'niveau' => null,
            'duree_secondes' => $this->faker->numberBetween(120, 900),
        ];
    }
}
