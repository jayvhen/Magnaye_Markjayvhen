<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\ShoeResource;
use App\Models\Shoe;

class ShoeController extends Controller
{
    /**
     * GET /api/v1/shoes
     * Returns a paginated list (15 per page). Each shoe is transformed by ShoeResource.
     */
    public function index()
    {
        return ShoeResource::collection(Shoe::paginate());
    }

    /**
     * GET /api/v1/shoes/{shoe}
     * Returns one shoe. Laravel loads it from the URL (route model binding)
     * and answers 404 by itself when it does not exist.
     */
    public function show(Shoe $shoe)
    {
        return new ShoeResource($shoe);
    }
}
