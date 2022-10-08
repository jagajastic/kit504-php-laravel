<?php

namespace App\Models;

use App\Enums\UserType;
use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shop extends Model
{
    use HasFactory;
    use ModelEssentialsTrait;

    /**
     * Return staffs working in a shop.
     */
    public function staffs(): HasMany
    {
        return $this->hasMany(User::class, 'shop_id', 'id');
    }

    /**
     * Get the shop manager.
     */
    public function getManagerAttribute(): ?User
    {
        return $this->staffs()->whereType(UserType::SHOP_MANAGER)->first();
    }
}
