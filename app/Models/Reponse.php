<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['reponse_eleve', 'est_correcte', 'id_tentative', 'id_question'])]
class Reponse extends Model
{
    protected function casts(): array
    {
        return [
            'est_correcte' => 'boolean',
        ];
    }

    public function tentative(): BelongsTo
    {
        return $this->belongsTo(Tentative::class, 'id_tentative');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class, 'id_question');
    }
}