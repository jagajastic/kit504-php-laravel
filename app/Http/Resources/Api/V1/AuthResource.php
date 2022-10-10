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

        $is_normal_user = \in_array($this->type, UserType::normalUsers(), \true);

        return  [
            'id'              => $this->id,
            'api_token'       => $uuid,
            'shop'            => $this->when(
                $this->shop_id !== \null && \in_array(
                    $this->type,
                    [UserType::SHOP_MANAGER, UserType::SHOP_STAFF]
                ),
                function () {
                    return new ShopResource($this->shop);
                }
            ),
            'first_name'      => $this->first_name,
            'last_name'       => $this->last_name,
            'type'            => $this->type,
            'account_balance' => $this->when($is_normal_user, function () {
                return $this->account_balance;
            }),
            'account_balance_usd' => $this->when($is_normal_user, function () {
                return $this->account_balance_usd;
            }),
        ];
    }
}
