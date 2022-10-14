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
            'user'         => new AuthResource($this->whenLoaded('user'), \false),
            'shop_id'      => $this->when(
                !$this->relationLoaded('shop'),
                $this->shop_id,
            ),
            'shop'         => new ShopResource($this->whenLoaded('shop')),
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
