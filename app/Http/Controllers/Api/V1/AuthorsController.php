<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\AuthorFilter;
use App\Http\Resources\V1\UserResource;
use App\Models\User;

/**
 * Read-only view of the users who have written shoes (the "authors").
 */
class AuthorsController extends APIController
{
    /**
     * GET /api/v1/authors
     * Lists only users who own at least one shoe, 15 per page.
     */
    public function index(AuthorFilter $filters)
    {
        // Authors only, with the filters and sorting from the URL applied
        return UserResource::collection(
            User::has('shoes')->filter($filters)->paginate()
        );
    }

    /**
     * GET /api/v1/authors/{author}
     * Returns one author (route model binding loads the User).
     */
    public function show(User $author)
    {
        // ?include=shoes: load this author's shoes
        if ($this->include('shoes')) {
            return new UserResource($author->load('shoes'));
        }

        return new UserResource($author);
    }
}
