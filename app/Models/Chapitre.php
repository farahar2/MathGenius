<?php

namespace App\Models;

use App\Models\Concerns\HasPublicationScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapitre extends Model
{
    use HasFactory, HasPublicationScope;

    protected $fillable = [
        'titre',
        'description',
        'image',
        'fichier_pdf',
        'ordre',
        'is_published',
        'id_niveau',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'is_published' => 'boolean',
    ];

    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'id_niveau');
    }

    public function lecons(): HasMany
    {
        return $this->hasMany(Lecon::class, 'id_chapitre');
    }

    public function quiz(): HasMany
    {
        return $this->hasMany(Quiz::class, 'id_chapitre');
    }

    public function recommandations(): HasMany
    {
        return $this->hasMany(Recommandation::class, 'id_chapitre');
    }
}
