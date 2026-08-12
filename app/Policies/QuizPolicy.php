<?php

namespace App\Policies;

use App\Models\User;
use App\Policies\Concerns\ManagesContentAccess;

class QuizPolicy
{
    use ManagesContentAccess;

    /**
     * Determine whether the user can request an AI-generated quiz.
     */
    public function generate(User $user): bool
    {
        return true;
    }
}
