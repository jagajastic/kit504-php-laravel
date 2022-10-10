<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use App\Http\Requests\BaseFormRequest;

class RegisterRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'first_name'    => ['required', 'string', 'min:2'],
            'last_name'     => ['required', 'string', 'min:2'],
            'email'         => ['required', 'email', 'unique:users,email'],
            'password'      => ['required', 'min:8'],
            'type'          => [
                'required',
                Rule::in(UserType::normalUsers()),
            ],
        ];
    }
}
