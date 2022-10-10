<?php

namespace App\Http\Resources\Api\V1;

use App\Enums\UserType;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $uuid = Str::orderedUuid()->toString();

        $this->apiTokens()->create(['value' => $uuid]);

        $can_order = \in_array(
            $this->type,
            [UserType::SHOP_STAFF, UserType::UTAS_EMPLOYEE, UserType::UTAS_STUDENT],
            \true
        );

        return  [
            'id'              => $this->id,
            'api_token'       => $uuid,
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'type'            => $this->type,
            'account_balance' => $this->when($can_order, function () {
                return $this->account_balance;
            }),
            'account_balance_usd' => $this->when($can_order, function () {
                return $this->account_balance_usd;
            }),
            'shop'            => $this->when(
                $this->shop_id !== \null && \in_array(
                    $this->type,
                    [UserType::SHOP_MANAGER, UserType::SHOP_STAFF]
                ),
                function () {
                    return new ShopResource($this->shop);
                }
            ),
        ];
    }
}
