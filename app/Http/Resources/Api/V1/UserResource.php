<?php

namespace App\Http\Resources\Api\V1;

use App\Enums\UserType;
use Illuminate\Support\Str;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    protected bool $generateToken;

    /**
     * @inheritDoc
     */
    public function __construct($resource, $generateToken = \true)
    {
        parent::__construct($resource);

        $this->generateToken = $generateToken;
    }

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $can_order = \in_array(
            $this->type,
            [UserType::SHOP_STAFF, UserType::UTAS_EMPLOYEE, UserType::UTAS_STUDENT]
        );

        return  [
            'id'              => $this->id,
            'api_token'       => $this->when(
                $this->generateToken,
                function () {
                    $uuid = Str::orderedUuid()->toString();

                    $this->apiTokens()->create(['value' => $uuid]);

                    return $uuid;
                }
            ),
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
