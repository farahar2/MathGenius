<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RecommandationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'             => $this->id,
            'message'        => $this->message,
            'is_lue'         => $this->is_lue,
            'id_chapitre'    => $this->id_chapitre,
            'id_utilisateur' => $this->id_utilisateur,
            'chapitre'       => $this->whenLoaded('chapitre', fn () => [
                'id'    => $this->chapitre?->id,
                'titre' => $this->chapitre?->titre,
            ]),
            'created_at'     => $this->created_at,
            'updated_at'     => $this->updated_at,
        ];
    }
}
