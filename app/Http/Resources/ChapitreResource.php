<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChapitreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'titre'       => $this->titre,
            'description' => $this->description,
            'ordre'       => $this->ordre,
            'is_published'=> $this->is_published,
            'id_niveau'   => $this->id_niveau,
            'niveau'      => $this->whenLoaded('niveau', fn () => [
                'id'  => $this->niveau?->id,
                'nom' => $this->niveau?->nom,
            ]),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
