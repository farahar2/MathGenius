<?php

namespace App\Policies;

use App\Models\Chapitre;
use App\Models\User;

class ChapitrePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Chapitre $chapitre): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function update(User $user, Chapitre $chapitre): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function delete(User $user, Chapitre $chapitre): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }
}
