<?php

namespace App\Ai\Agents;

use Illuminate\Contracts\JsonSchema\JsonSchema;
use Laravel\Ai\Attributes\Provider;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\HasStructuredOutput;
use Laravel\Ai\Enums\Lab;
use Laravel\Ai\Promptable;
use Stringable;

#[Provider(Lab::Gemini)]
class QuizGeneratorAgent implements Agent, HasStructuredOutput
{
    use Promptable;

    /**
     * Get the instructions that the agent should follow.
     */
    public function instructions(): Stringable|string
    {
        return <<<'INSTRUCTIONS'
            Tu es un professeur de mathématiques marocain, expert du programme du lycée
            (Tronc Commun, 1ère année Bac, 2ème année Bac).

            À partir du contenu de leçon fourni par l'utilisateur, génère des questions de
            quiz à choix multiple (QCM) en français, en respectant ces règles :
            - Chaque question a exactement 4 options (A, B, C, D) et une seule bonne réponse.
            - Chaque question inclut une explication pédagogique courte de la bonne réponse.
            - Chaque question précise la notion mathématique évaluée.
            - Les questions se basent strictement sur le contenu de la leçon fournie, et
              respectent le niveau et la difficulté demandés.
            - Génère exactement le nombre de questions demandé.
            INSTRUCTIONS;
    }

    /**
     * Get the agent's structured output schema definition.
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'questions' => $schema->array()->items(
                $schema->object([
                    'question' => $schema->string()->required(),
                    'option_a' => $schema->string()->required(),
                    'option_b' => $schema->string()->required(),
                    'option_c' => $schema->string()->required(),
                    'option_d' => $schema->string()->required(),
                    'bonne_reponse' => $schema->string()->enum(['A', 'B', 'C', 'D'])->required(),
                    'explication' => $schema->string()->required(),
                    'notion' => $schema->string()->required(),
                ])
            )->min(1)->required(),
        ];
    }
}
