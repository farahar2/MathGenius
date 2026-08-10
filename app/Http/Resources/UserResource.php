<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'         => $this->id,
            'name'       => $this->name,
            'prenom'     => $this->prenom,
            'email'      => $this->email,
            'role'       => $this->role,
            'is_premium' => $this->is_premium,
            'filiere'    => $this->whenLoaded('filiere', fn () => [
                'id'  => $this->filiere?->id,
                'nom' => $this->filiere?->nom,
            ]),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
