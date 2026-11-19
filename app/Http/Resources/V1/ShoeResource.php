<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Transforms a Shoe model into the JSON structure our API returns.
 */
class ShoeResource extends JsonResource
{
    /**
     * Build the array that becomes the JSON response.
     */
    public function toArray(Request $request): array
    {
        return [
            // What kind of object this is
            'type' => 'shoe',
            'id' => $this->id,
            // The shoe's own fields, renamed to camelCase for the client
            'attributes' => [
                'shoe' => $this->shoe,
                // Shown on the detail page only: hidden on the list endpoints
                'description' => $this->when(
                    ! $request->routeIs(['shoes.index', 'users.shoes.index']),
                    $this->description
                ),
                'status' => $this->status,
                'createdAt' => $this->created_at,
                'updatedAt' => $this->updated_at,
            ],
            // Related objects: for now only the id of the author
            'relationships' => [
                'author' => [
                    'data' => [
                        'type' => 'user',
                        'id' => $this->user_id,
                    ],
                    // Link to the author's own endpoint
                    'links' => [
                        'self' => route('authors.show', ['author' => $this->user_id]),
                    ],
                ],
            ],
            // The author object, present only when requested with ?include=author
            'includes' => new UserResource($this->whenLoaded('author')),
            // Link to this shoe
            'links' => [
                'self' => route('shoes.show', ['shoe' => $this->id]),
            ],
        ];
    }
}
