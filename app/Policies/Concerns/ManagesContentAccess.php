<?php

namespace App\Policies\Concerns;

use App\Models\User;

trait ManagesContentAccess
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function update(User $user, $model): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function delete(User $user, $model): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }
}
