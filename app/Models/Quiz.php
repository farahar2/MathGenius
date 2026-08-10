<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['id_lecon', 'id_chapitre', 'difficulte', 'niveau', 'duree_secondes'])]
class Quiz extends Model
{
    protected $table = 'quiz';

    protected function casts(): array
    {
        return [
            'duree_secondes' => 'integer',
        ];
    }

    public function lecon(): BelongsTo
    {
        return $this->belongsTo(Lecon::class, 'id_lecon');
    }

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'id_chapitre');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class, 'id_quiz');
    }

    public function tentatives(): HasMany
    {
        return $this->hasMany(Tentative::class, 'id_quiz');
    }
}