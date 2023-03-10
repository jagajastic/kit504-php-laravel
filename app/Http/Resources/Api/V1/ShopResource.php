<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class ShopResource extends JsonResource
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
            'name'         => $this->name,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'created_at'   => $this->created_at,
            'updated_at'   => $this->updated_at,
        ];
    }
}
