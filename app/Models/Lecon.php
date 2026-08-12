<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lecon extends Model
{
    use HasFactory, HasPublicationScope;

    protected $fillable = [
        'titre',
        'contenu',
        'image',
        'fichier_pdf',
        'ordre',
        'is_published',
        'id_chapitre',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'is_published' => 'boolean',
    ];

    public function chapitre(): BelongsTo
    {
        return $this->belongsTo(Chapitre::class, 'id_chapitre');
    }

    public function exercices(): HasMany
    {
        return $this->hasMany(Exercice::class, 'id_lecon');
    }

    public function quiz(): HasMany
    {
        return $this->hasMany(Quiz::class, 'id_lecon');
    }
}
