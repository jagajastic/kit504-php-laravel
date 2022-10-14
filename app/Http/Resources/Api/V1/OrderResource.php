<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
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
            'id'           => $this->id,
            'user_id'      => $this->when(
                !$this->relationLoaded('user'),
                $this->user_id,
            ),
            'shop_id'      => $this->when(
                !$this->relationLoaded('shop'),
                $this->shop_id,
            ),
            'total_price'       => $this->total_price,
            'total_price_usd'   => $this->total_price_usd,
            'user'              => new UserResource($this->whenLoaded('user'), \false),
            'shop'              => new ShopResource($this->whenLoaded('shop')),
            'items'             => OrderItemResource::collection($this->whenLoaded('items')),
            'created_at'        => $this->created_at,
            'updated_at'        => $this->updated_at,
        ];
    }
}
