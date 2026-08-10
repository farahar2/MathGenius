<?php

namespace App\Policies;

use App\Models\Niveau;
use App\Models\User;

class NiveauPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Niveau $niveau): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function update(User $user, Niveau $niveau): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function delete(User $user, Niveau $niveau): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }
}
