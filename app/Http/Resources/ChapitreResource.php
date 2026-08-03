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
            'id_filiere'  => $this->id_filiere,
            'filiere'     => $this->whenLoaded('filiere', fn () => [
                'id'  => $this->filiere?->id,
                'nom' => $this->filiere?->nom,
            ]),
            'created_at'  => $this->created_at,
            'updated_at'  => $this->updated_at,
        ];
    }
}
