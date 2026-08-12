<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question' => $this->faker->sentence().' ?',
            'option_a' => $this->faker->word(),
            'option_b' => $this->faker->word(),
            'option_c' => $this->faker->word(),
            'option_d' => $this->faker->word(),
            'bonne_reponse' => $this->faker->randomElement(['A', 'B', 'C', 'D']),
            'explication' => $this->faker->sentence(),
            'notion' => $this->faker->word(),
            'ordre' => $this->faker->numberBetween(1, 20),
            'id_quiz' => Quiz::factory(),
        ];
    }

    /**
     * Force la bonne réponse, pour les tests de calcul de score.
     */
    public function correctAnswer(string $lettre): static
    {
        return $this->state(fn () => ['bonne_reponse' => $lettre]);
    }
}
