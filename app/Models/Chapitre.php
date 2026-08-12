<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chapitre extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'description',
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
}
