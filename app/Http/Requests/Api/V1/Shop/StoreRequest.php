<?php

namespace App\Http\Requests\Api\V1\Shop;

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
            'name'         => ['required', 'string', 'min:1'],
            'opening_time' => ['required', 'string', 'date_format:H:i'],
            'closing_time' => ['required', 'string', 'date_format:H:i'],
        ];
    }
}
