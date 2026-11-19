<?php

namespace App\Http\Requests\Api\V1;

/**
 * Validates the body of POST /shoes and POST /users/{user}/shoes.
 */
class StoreShoeRequest extends BaseShoeRequest
{
    /**
     * Who may send this request is decided later by ShoePolicy (Step 19),
     * so this class always allows it.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules for creating a shoe.
     */
    public function rules(): array
    {
        // Where the author id comes from: the body on /shoes,
        // or the URL (merged into "user" below) on /users/{user}/shoes
        $userIdAttr = $this->routeIs('shoes.store')
            ? 'data.relationships.user.data.id'
            : 'user';

        return [
            'data' => 'required|array',
            'data.attributes' => 'required|array',
            'data.attributes.shoe' => 'required|string',
            'data.attributes.description' => 'required|string',
            'data.attributes.status' => 'required|string|in:A,C,H,X',
            $userIdAttr => 'required|integer|exists:users,id',
        ];
    }

    /**
     * Runs before validation. On /users/{user}/shoes the author id is in the URL,
     * so we copy it into a "user" field that the rules above can validate.
     */
    protected function prepareForValidation(): void
    {
        if ($this->routeIs('users.shoes.store')) {
            $this->merge([
                'user' => $this->route('user')->getKey(),
            ]);
        }
    }
}
