<?php

namespace App\JsonApi\V1\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'type'                     => 'in:auth',
            'data.attributes.email'    => ['required', 'email'],
            'data.attributes.password' => ['required', 'min:8'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'data.attributes.email'    => 'email',
            'data.attributes.password' => 'password',
        ];
    }
}
