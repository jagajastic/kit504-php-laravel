<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id'       => $this->id,
            'quantity' => $this->quantity,
            'comment'  => $this->comment,
            'product'  => [
                'id'        => $this->product_id,
                'price'     => $this->product_price,
                'price_usd' => $this->product_price_usd,
                'name'      => $this->product_name,
                'image'     => $this->product_image,
            ],
        ];
    }
}
