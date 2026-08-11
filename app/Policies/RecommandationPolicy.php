<?php

namespace App\Policies;

use App\Models\Recommandation;
use App\Models\User;

class RecommandationPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recommandation $recommandation): bool
    {
        return $user->id === $recommandation->id_utilisateur;
    }

    public function delete(User $user, Recommandation $recommandation): bool
    {
        return $user->id === $recommandation->id_utilisateur;
    }
}
