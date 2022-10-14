<?php

namespace App\Http\Requests\Api\V1\Auth;

use App\Http\Requests\BaseFormRequest;

class UpdateRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'account_balance' => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
