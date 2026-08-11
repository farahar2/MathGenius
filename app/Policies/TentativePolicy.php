<?php

namespace App\Policies;

use App\Models\Tentative;
use App\Models\User;

class TentativePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Tentative $tentative): bool
    {
        return $user->id === $tentative->id_utilisateur;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function delete(User $user, Tentative $tentative): bool
    {
        return $user->id === $tentative->id_utilisateur;
    }
}
