<?php

namespace Database\Factories;

use App\Models\Quiz;
use App\Models\Tentative;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Tentative>
 */
class TentativeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $score = $this->faker->numberBetween(0, 10);

        return [
            'score' => $score,
            'score_pct' => $score * 10,
            'analyse_ia' => null,
            'recomm_ia' => null,
            'completed_at' => now(),
            'id_quiz' => Quiz::factory(),
            'id_utilisateur' => User::factory(),
        ];
    }
}
