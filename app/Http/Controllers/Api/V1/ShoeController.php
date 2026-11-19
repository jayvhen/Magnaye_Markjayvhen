<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Filters\V1\ShoeFilter;
use App\Http\Requests\Api\V1\ReplaceShoeRequest;
use App\Http\Requests\Api\V1\StoreShoeRequest;
use App\Http\Requests\Api\V1\UpdateShoeRequest;
use App\Http\Resources\V1\ShoeResource;
use App\Models\Shoe;

class ShoeController extends APIController
{
    /**
     * GET /api/v1/shoes
     * Returns a paginated list (15 per page). Each shoe is transformed by ShoeResource.
     */
    public function index(ShoeFilter $filters)
    {
        // Apply the filters from the URL (?status=A, ?shoe=*x*, ?include=author ...)
        return ShoeResource::collection(
            Shoe::filter($filters)->paginate()
        );
    }

    /**
     * GET /api/v1/shoes/{shoe}
     * Returns one shoe. Laravel loads it from the URL (route model binding)
     * and answers 404 by itself when it does not exist.
     */
    public function show(Shoe $shoe)
    {
        // ?include=author: load the author of this shoe
        if ($this->include('author')) {
            return new ShoeResource($shoe->load('author'));
        }

        return new ShoeResource($shoe);
    }

    /**
     * POST /api/v1/shoes
     * Validates the body with StoreShoeRequest, then creates the shoe.
     * Laravel answers 201 Created automatically for a newly created resource.
     */
    public function store(StoreShoeRequest $request)
    {
        // mappedAttributes() converts the nested JSON into database columns
        return new ShoeResource(
            Shoe::create($request->mappedAttributes())
        );
    }

    /**
     * DELETE /api/v1/shoes/{shoe}
     * Deletes the shoe. A missing shoe never reaches this method: Laravel answers 404.
     */
    public function destroy(Shoe $shoe)
    {
        $shoe->delete();

        return $this->ok('Shoe successfully deleted');
    }

    /**
     * PUT /api/v1/shoes/{shoe}
     * Replaces the whole shoe: every field must be sent (see ReplaceShoeRequest).
     */
    public function replace(ReplaceShoeRequest $request, Shoe $shoe)
    {
        $shoe->update($request->mappedAttributes());

        return new ShoeResource($shoe);
    }

    /**
     * PATCH /api/v1/shoes/{shoe}
     * Updates only the fields that were sent, but only if the policy allows it.
     */
    public function update(UpdateShoeRequest $request, Shoe $shoe)
    {
        // Ask ShoePolicy::update() (owner, or a user holding the broader ability)
        if ($this->isAble('update', $shoe)) {
            $shoe->update($request->mappedAttributes());

            return new ShoeResource($shoe);
        }

        return $this->notAuthorized('You are not authorized to update that resource.');
    }
}
