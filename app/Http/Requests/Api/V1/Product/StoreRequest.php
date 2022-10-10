<?php

namespace App\Http\Requests\Api\V1\Product;

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
            'name'  => [$this->requiredRule(), 'string', 'min:2'],
            'price' => [$this->requiredRule(), 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'mimes:jpg,png'],
        ];
    }
}
