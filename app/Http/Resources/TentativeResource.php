<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TentativeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'score'          => $this->score,
            'score_pct'      => $this->score_pct,
            'analyse_ia'     => $this->analyse_ia,
            'recomm_ia'      => $this->recomm_ia,
            'completed_at'   => $this->completed_at,
            'id_quiz'        => $this->id_quiz,
            'id_utilisateur' => $this->id_utilisateur,
            'quiz'           => $this->whenLoaded('quiz', fn () => new QuizResource($this->quiz)),
            'reponses'       => $this->whenLoaded('reponses', fn () => $this->reponses->map(fn ($reponse) => [
                'id'            => $reponse->id,
                'id_question'   => $reponse->id_question,
                'reponse_eleve' => $reponse->reponse_eleve,
                'est_correcte'  => $reponse->est_correcte,
            ])),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
