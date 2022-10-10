<?php

namespace App\Http\Requests\Api\V1\Product;

class UpdateRequest extends StoreRequest
{
    /**
     * @inheritDoc
     */
    protected $updating = true;
}
