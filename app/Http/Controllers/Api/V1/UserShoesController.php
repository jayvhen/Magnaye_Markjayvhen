<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ShoeFilter;
use App\Http\Resources\V1\ShoeResource;
use App\Models\Shoe;
use App\Models\User;

/**
 * Shoes that belong to one user: /api/v1/users/{user}/shoes
 */
class UserShoesController extends APIController
{
    /**
     * GET /api/v1/users/{user}/shoes
     * Lists only this user's shoes. All ShoeFilter parameters work here too.
     */
    public function index(User $user, ShoeFilter $filters)
    {
        return ShoeResource::collection(
            Shoe::where('user_id', $user->id)->filter($filters)->paginate()
        );
    }
}
