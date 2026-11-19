<?php

namespace App\Http\Requests\Api\V1;

/**
 * Validates the body of PUT /shoes/{shoe}: every field is required.
 */
class ReplaceShoeRequest extends BaseShoeRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * PUT replaces the whole resource, so nothing may be missing.
     */
    public function rules(): array
    {
        return [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.shoe' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            'data.relationships' => 'required|array',
            'data.relationships.user' => 'required|array',
            'data.relationships.user.data' => 'required|array',
            'data.relationships.user.data.id' => 'required|integer|exists:users,id',
        ];
    }
}
