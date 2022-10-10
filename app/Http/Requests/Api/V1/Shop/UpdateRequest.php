<?php

namespace App\Http\Requests\Api\V1\Shop;

class UpdateRequest extends StoreRequest
{
    /**
     * @inheritDoc
     */
    protected $updating = true;
}
