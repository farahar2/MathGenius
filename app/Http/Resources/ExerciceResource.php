<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExerciceResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'titre'        => $this->titre,
            'enonce'       => $this->enonce,
            // Le corrigé fait partie du contenu pédagogique destiné aux
            // élèves, mais il n'a pas à être servi à des visiteurs anonymes.
            'correction'   => $this->when($request->user() !== null, fn () => $this->correction),
            'image'        => $this->image,
            'fichier_pdf'  => $this->fichier_pdf,
            'ordre'        => $this->ordre,
            'is_published' => $this->is_published,
            'id_lecon'     => $this->id_lecon,
            'lecon'        => $this->whenLoaded('lecon', fn () => [
                'id'    => $this->lecon?->id,
                'titre' => $this->lecon?->titre,
            ]),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
