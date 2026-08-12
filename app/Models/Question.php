<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['question', 'option_a', 'option_b', 'option_c', 'option_d', 'bonne_reponse', 'explication', 'notion', 'ordre', 'id_quiz'])]
class Question extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'ordre' => 'integer',
        ];
    }

    public function quiz(): BelongsTo
    {
        return $this->belongsTo(Quiz::class, 'id_quiz');
    }

    public function reponses(): HasMany
    {
        return $this->hasMany(Reponse::class, 'id_question');
    }
}