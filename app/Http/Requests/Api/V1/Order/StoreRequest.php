<?php

namespace App\Http\Requests\Api\V1\Order;

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
            'shop_id' => ['required', 'string', 'uuid', 'exists:shops,id'],
        ];
    }
}
