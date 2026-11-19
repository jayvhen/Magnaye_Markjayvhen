<?php

namespace App\Http\Requests\Api\V1;

use App\Permissions\V1\Abilities;

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
     * Validation rules for updating a shoe.
     */
    public function rules(): array
    {
        $rules = [
            'data.attributes.shoe' => 'sometimes|string',
            'data.attributes.description' => 'sometimes|string',
            'data.attributes.status' => 'sometimes|string|in:A,C,H,X',
            'data.relationships.user.data.id' => 'sometimes|integer|exists:users,id',
        ];

        // Users who may only update their own shoes cannot change the author
        if ($this->user()->tokenCan(Abilities::UpdateOwnShoe)) {
            $rules['data.relationships.user.data.id'] = 'prohibited';
        }

        return $rules;
    }
}
