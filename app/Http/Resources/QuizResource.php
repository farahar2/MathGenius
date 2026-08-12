<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'id_lecon'       => $this->id_lecon,
            'id_chapitre'    => $this->id_chapitre,
            'difficulte'     => $this->difficulte,
            'niveau'         => $this->niveau,
            'duree_secondes' => $this->duree_secondes,
            'lecon'          => $this->whenLoaded('lecon', fn () => [
                'id'    => $this->lecon?->id,
                'titre' => $this->lecon?->titre,
            ]),
            'questions'      => QuestionResource::collection($this->whenLoaded('questions')),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
