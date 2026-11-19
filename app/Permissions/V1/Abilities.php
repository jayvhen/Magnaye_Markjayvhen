<?php

namespace App\Permissions\V1;

use App\Models\User;

/**
 * Every ability name in one place, plus the rule that decides who receives which.
 * Format: resource:action. "own" means "only for things I own".
 */
final class Abilities
{
    // Shoe abilities
    public const CreateShoe = 'shoe:create';

    public const CreateOwnShoe = 'shoe:own:create';

    public const UpdateShoe = 'shoe:update';

    public const UpdateOwnShoe = 'shoe:own:update';

    public const ReplaceShoe = 'shoe:replace';

    public const DeleteShoe = 'shoe:delete';

    public const DeleteOwnShoe = 'shoe:own:delete';

    // User management abilities (managers only)
    public const CreateUser = 'user:create';

    public const UpdateUser = 'user:update';

    public const ReplaceUser = 'user:replace';

    public const DeleteUser = 'user:delete';

    /**
     * Returns the list of abilities a user receives. The list is written into the
     * JWT at login, so the policies can read it without querying the database.
     */
    public static function getAbilities(User $user): array
    {
        // Managers may act on anything
        if ($user->is_manager) {
            return [
                self::CreateShoe,
                self::UpdateShoe,
                self::ReplaceShoe,
                self::DeleteShoe,
                self::CreateUser,
                self::UpdateUser,
                self::ReplaceUser,
                self::DeleteUser,
            ];
        }

        // Regular users may only act on their own shoes
        return [
            self::CreateOwnShoe,
            self::UpdateOwnShoe,
            self::DeleteOwnShoe,
        ];
    }
}
