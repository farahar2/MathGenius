<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeconResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'titre'        => $this->titre,
            'contenu'      => $this->contenu,
            'image'        => $this->image,
            'fichier_pdf'  => $this->fichier_pdf,
            'ordre'        => $this->ordre,
            'is_published' => $this->is_published,
            'id_chapitre'  => $this->id_chapitre,
            'chapitre'     => $this->whenLoaded('chapitre', fn () => [
                'id'    => $this->chapitre?->id,
                'titre' => $this->chapitre?->titre,
            ]),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
