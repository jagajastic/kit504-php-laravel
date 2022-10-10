<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\BaseFormRequest;

class LoginRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'    => ['required', 'email'],
            'password' => ['required', $this->getPasswordRule()],
        ];
    }
}
