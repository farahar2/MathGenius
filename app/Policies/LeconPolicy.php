<?php

namespace App\Policies;

use App\Policies\Concerns\ManagesContentAccess;

class LeconPolicy
{
<<<<<<< Updated upstream
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
        return $user->isAdmin();
    }

    public function update(User $user, Lecon $lecon): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Lecon $lecon): bool
    {
        return $user->isAdmin();
    }
=======
    use ManagesContentAccess;
>>>>>>> Stashed changes
}
