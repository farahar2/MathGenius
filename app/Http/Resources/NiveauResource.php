<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NiveauResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'nom'        => $this->nom,
            'ordre'      => $this->ordre,
            'chapitres'  => ChapitreResource::collection($this->whenLoaded('chapitres')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
