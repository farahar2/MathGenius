<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationScope;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['titre', 'enonce', 'correction', 'image', 'fichier_pdf', 'ordre', 'is_published', 'id_lecon'])]
class Exercice extends Model
{
    use HasFactory, HasPublicationScope;

    protected function casts(): array
    {
        return [
            'ordre' => 'integer',
            'is_published' => 'boolean',
        ];
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class, 'id_lecon');
    }
}