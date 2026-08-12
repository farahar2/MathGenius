<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'            => $this->id,
            'question'      => $this->question,
            'option_a'      => $this->option_a,
            'option_b'      => $this->option_b,
            'option_c'      => $this->option_c,
            'option_d'      => $this->option_d,
            'bonne_reponse' => $this->bonne_reponse,
            'explication'   => $this->explication,
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
