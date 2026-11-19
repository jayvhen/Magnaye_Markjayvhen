<?php

namespace App\Http\Requests\Api\V1;

/**
 * Validates the body of PATCH /shoes/{shoe}: only the fields sent are checked.
 */
class UpdateShoeRequest extends BaseShoeRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for updating a shoe. "sometimes" means:
     * validate the field only when it was sent.
     */
    public function rules(): array
    {
        return [
            'data.attributes.shoe' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            'data.relationships.user.data.id' => 'sometimes|integer|exists:users,id',
        ];
    }
}
