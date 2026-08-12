<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

/**
 * Restreint le contenu non publié aux seuls gestionnaires de contenu.
 *
 * Les tables `chapitres`, `lecons` et `exercices` portent un drapeau
 * `is_published` qui n'était jusqu'ici jamais consulté : un brouillon
 * était servi à tout le monde par l'API.
 */
trait HasPublicationScope
{
    /**
     * Limite la requête au contenu que l'utilisateur a le droit de voir.
     * Les formateurs et admins voient aussi les brouillons.
     */
    public function scopeVisibleTo(Builder $query, ?User $user): Builder
    {
        if ($user && ($user->isFormateur() || $user->isAdmin())) {
            return $query;
        }

        return $query->where('is_published', true);
    }

    /**
     * Le contenu est-il consultable par cet utilisateur ?
     */
    public function isVisibleTo(?User $user): bool
    {
        if ($user && ($user->isFormateur() || $user->isAdmin())) {
            return true;
        }

        return (bool) $this->is_published;
    }
}
