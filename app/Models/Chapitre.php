<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapitre extends Model
{
    protected $fillable = [
        'titre',
        'description',
        'ordre',
        'is_published',
        'id_filiere',
    ];

    protected $casts = [
        'ordre' => 'integer',
        'is_published' => 'boolean',
    ];

    public function filiere(): BelongsTo
    {
        return $this->belongsTo(Filiere::class, 'id_filiere');
    }

    public function lecons(): HasMany
    {
        return $this->hasMany(Lecon::class, 'id_chapitre');
    }
}
