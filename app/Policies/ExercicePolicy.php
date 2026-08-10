<?php

namespace App\Policies;

use App\Models\Exercice;
use App\Models\User;

class ExercicePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Exercice $exercice): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function update(User $user, Exercice $exercice): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function delete(User $user, Exercice $exercice): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }
}
