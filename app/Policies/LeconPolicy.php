<?php

namespace App\Policies;

use App\Models\Lecon;
use App\Models\User;

class LeconPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Lecon $lecon): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function update(User $user, Lecon $lecon): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }

    public function delete(User $user, Lecon $lecon): bool
    {
        return $user->isFormateur() || $user->isAdmin();
    }
}
