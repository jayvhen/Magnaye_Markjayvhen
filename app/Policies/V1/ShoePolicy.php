<?php

namespace App\Policies\V1;

use App\Models\Shoe;
use App\Models\User;
use App\Permissions\V1\Abilities;

/**
 * Authorization rules for shoes. Each method returns true (allowed) or false (denied).
 * The rules read the abilities stored in the user's JWT.
 */
class ShoePolicy
{
    /**
     * May this user create a shoe? Needs the broad or the "own" create ability.
     */
    public function store(User $user): bool
    {
        return $user->tokenCan(Abilities::CreateShoe)
            || $user->tokenCan(Abilities::CreateOwnShoe);
    }

    /**
     * May this user update this shoe (PATCH)?
     */
    public function update(User $user, Shoe $shoe): bool
    {
        // Broad ability: any shoe
        if ($user->tokenCan(Abilities::UpdateShoe)) {
            return true;
        }

        // "Own" ability: only a shoe the user owns
        if ($user->tokenCan(Abilities::UpdateOwnShoe)) {
            return $user->id === $shoe->user_id;
        }

        return false;
    }

    /**
     * May this user replace this shoe (PUT)? Managers only.
     */
    public function replace(User $user, Shoe $shoe): bool
    {
        return $user->tokenCan(Abilities::ReplaceShoe);
    }

    /**
     * May this user delete this shoe?
     */
    public function delete(User $user, Shoe $shoe): bool
    {
        // Broad ability: any shoe
        if ($user->tokenCan(Abilities::DeleteShoe)) {
            return true;
        }

        // "Own" ability: only a shoe the user owns
        if ($user->tokenCan(Abilities::DeleteOwnShoe)) {
            return $user->id === $shoe->user_id;
        }

        return false;
    }
}
