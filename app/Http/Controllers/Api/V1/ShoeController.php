<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Shoe;

class ShoeController extends Controller
{
    /**
     * GET /api/v1/shoes
     * Returns every shoe (pagination and filters come in later steps).
     */
    public function index()
    {
        return Shoe::all();
    }
}
