<?php

namespace App\JsonApi\V1\Auth;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            'type'                          => 'in:auth',
            'data.attributes.first_name'    => ['required', 'string', 'min:2'],
            'data.attributes.last_name'     => ['required', 'string', 'min:2'],
            'data.attributes.email'         => ['required', 'email', 'unique:users,email'],
            'data.attributes.password'      => ['required', 'min:8'],
            'data.attributes.type'          => [
                'required',
                Rule::in(UserType::normalUsers()),
            ],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributes(): array
    {
        return [
            'data.attributes.first_name' => 'first name',
            'data.attributes.last_name'  => 'last name',
            'data.attributes.email'      => 'email',
            'data.attributes.password'   => 'password',
            'data.attributes.type'       => 'user type',
        ];
    }
}
