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
        $user = $this->resource;

        $uuid = Str::orderedUuid()->toString();

        $user->apiTokens()->create(['value' => $uuid]);

        $hidden = [];

        $included = [
            'api_token' => $uuid,
        ];

        $is_normal_user = \in_array($user->type, UserType::normalUsers());

        if ($is_normal_user) {
            $included['account_balance_usd'] = $user->account_balance_usd;
        } else {
            $hidden[] = 'account_balance';
        }

        if ($is_normal_user || $user->type === UserType::DIRECTOR) {
            $hidden[] = 'shop_id';
        }

        return collect($user)
            ->except($hidden)
            ->merge($included)
            ->toArray();
    }
}
