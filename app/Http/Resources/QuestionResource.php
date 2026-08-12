<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    /**
     * La bonne réponse ne doit jamais être lisible avant d'avoir répondu,
     * sinon le quiz n'a aucune valeur : sans ce filtre, un simple
     * `GET /api/questions` livre le corrigé de tous les quiz.
     *
     * Elle n'est révélée que dans deux cas :
     * - l'utilisateur est formateur ou admin (il gère le contenu) ;
     * - la question est sérialisée depuis le détail d'une tentative, que
     *   TentativePolicy::view réserve déjà à son propriétaire.
     */
    private function shouldRevealAnswer(Request $request): bool
    {
        $user = $request->user();

        if ($user && ($user->isFormateur() || $user->isAdmin())) {
            return true;
        }

        return $request->routeIs('tentatives.show');
    }

    public function toArray(Request $request): array
    {
        $reveal = $this->shouldRevealAnswer($request);

        return [
            'id'            => $this->id,
            'question'      => $this->question,
            'option_a'      => $this->option_a,
            'option_b'      => $this->option_b,
            'option_c'      => $this->option_c,
            'option_d'      => $this->option_d,
            'bonne_reponse' => $this->when($reveal, fn () => $this->bonne_reponse),
            'explication'   => $this->when($reveal, fn () => $this->explication),
            'notion'        => $this->notion,
            'ordre'         => $this->ordre,
            'id_quiz'       => $this->id_quiz,
            'quiz'          => $this->whenLoaded('quiz', fn () => [
                'id' => $this->quiz?->id,
            ]),
            'created_at'    => $this->created_at,
            'updated_at'    => $this->updated_at,
        ];
    }
}
