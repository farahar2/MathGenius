<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

<<<<<<< HEAD
#[Fillable(['name', 'email', 'password'])]
=======
#[Fillable(['name', 'prenom', 'email', 'password', 'role', 'is_premium', 'niveau_id'])]
>>>>>>> d5a2808 (add Niveau, Tentative and update User models)
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
<<<<<<< HEAD
=======

    /**
     * Get the niveau that owns the user.
     */
    public function niveau(): BelongsTo
    {
        return $this->belongsTo(Niveau::class, 'niveau_id');
    }

    /**
     * Get the quiz attempts for the user.
     */
    public function tentatives(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Tentative::class, 'id_utilisateur');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isPremium(): bool
    {
        return $this->is_premium === true;
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    public function fullName(): string
    {
        return "{$this->prenom} {$this->name}";
    }
>>>>>>> d5a2808 (add Niveau, Tentative and update User models)
}
