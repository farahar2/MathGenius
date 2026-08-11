<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['nom', 'ordre'])]
class Niveau extends Model
{
    use HasFactory;

    public function chapitres(): HasMany
    {
        return $this->hasMany(Chapitre::class, 'id_niveau');
    }

    public function utilisateurs(): HasMany
    {
        return $this->hasMany(User::class, 'niveau_id');
    }
}
