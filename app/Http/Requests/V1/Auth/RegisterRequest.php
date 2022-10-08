<?php

namespace App\Http\Requests\V1\Auth;

use App\Enums\UserType;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \true;
    }

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
