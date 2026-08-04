<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lecon extends Model
{
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
}
