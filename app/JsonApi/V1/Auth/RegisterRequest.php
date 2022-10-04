<?php

namespace App\JsonApi\V1\Auth;

use LaravelJsonApi\Laravel\Http\Requests\ResourceRequest;

class RegisterRequest extends ResourceRequest
{
    /**
     * Get the validation rules for the resource.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }
}
