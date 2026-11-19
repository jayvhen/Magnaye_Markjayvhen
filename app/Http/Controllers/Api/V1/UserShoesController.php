<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ShoeFilter;
use App\Http\Requests\Api\V1\ReplaceShoeRequest;
use App\Http\Requests\Api\V1\StoreShoeRequest;
use App\Http\Requests\Api\V1\UpdateShoeRequest;
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

    /**
     * POST /api/v1/users/{user}/shoes
     * Creates a shoe for the user named in the URL.
     */
    public function store(StoreShoeRequest $request, User $user)
    {
        if ($this->isAble('store', Shoe::class)) {
            // ['user' => 'user_id'] turns the id taken from the URL into the user_id column
            return new ShoeResource(
                Shoe::create($request->mappedAttributes(['user' => 'user_id']))
            );
        }

        return $this->notAuthorized('You are not authorized to create that resource.');
    }

    /**
     * PUT /api/v1/users/{user}/shoes/{shoe}
     * Replaces the whole shoe (managers only).
     */
    public function replace(ReplaceShoeRequest $request, User $user, Shoe $shoe)
    {
        if ($this->isAble('replace', $shoe)) {
            $shoe->update($request->mappedAttributes());

            return new ShoeResource($shoe);
        }

        return $this->notAuthorized('You are not authorized to replace that resource.');
    }

    /**
     * PATCH /api/v1/users/{user}/shoes/{shoe}
     * Updates only the fields that were sent.
     */
    public function update(UpdateShoeRequest $request, User $user, Shoe $shoe)
    {
        if ($this->isAble('update', $shoe)) {
            $shoe->update($request->mappedAttributes());

            return new ShoeResource($shoe);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }

    /**
     * DELETE /api/v1/users/{user}/shoes/{shoe}
     * Deletes the shoe. The scoped binding returns 404 if it is not this user's shoe.
     */
    public function destroy(User $user, Shoe $shoe)
    {
        if ($this->isAble('delete', $shoe)) {
            $shoe->delete();

            return $this->ok('Shoe successfully deleted');
        }

        return $this->notAuthorized('You are not authorized to delete that resource.');
    }
}
