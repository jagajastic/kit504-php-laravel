<?php

namespace App\Http\Requests\Api\V1\Shop;

use App\Enums\UserType;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->type === UserType::DIRECTOR;
    }

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
