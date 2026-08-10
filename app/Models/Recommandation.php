<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['message', 'is_lue', 'id_chapitre', 'id_utilisateur'])]
class Recommandation extends Model
{
    protected function casts(): array
    {
        return [
            'is_lue' => 'boolean',
        ];
    }

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'id_chapitre');
    }

    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_utilisateur');
    }
}