<?php

namespace App\Http\Resources\Api\V1;

use stdClass;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        if (!$this->resource instanceof stdClass) {
            $this->resource = (object) $this->resource;
        }

        return [
            'product'  => new ProductResource(Product::find($this->id)),
            'quantity' => $this->quantity,
            'comment'  => $this->comment,
        ];
    }
}
