<?php

namespace App\Http\Requests\Api\V1\Cart;

use App\Http\Requests\BaseFormRequest;

class StoreRequest extends BaseFormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'product_id' => ['required', 'uuid'],
            'comment'    => ['sometimes', 'nullable', 'string', 'min:2'],
            'quantity'   => ['sometimes', 'integer', 'min:1'],
        ];
    }
}
