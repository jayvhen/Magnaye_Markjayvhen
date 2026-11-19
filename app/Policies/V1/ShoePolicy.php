<?php

namespace App\Policies\V1;

use App\Models\Shoe;
use App\Models\User;

/**
 * Authorization rules for shoes. Each method returns true (allowed) or false (denied).
 */
class ShoePolicy
{
    /**
     * May this user update this shoe? Only its owner may (for now).
     */
    public function update(User $user, Shoe $shoe): bool
    {
        return $user->id === $shoe->user_id;
    }
}
