<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Reponse;
use App\Models\Tentative;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Reponse>
 */
class ReponseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'reponse_eleve' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'est_correcte' => $this->faker->boolean(),
            'id_tentative' => Tentative::factory(),
            'id_question' => Question::factory(),
        ];
    }
}
