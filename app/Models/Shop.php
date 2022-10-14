<?php

namespace App\Models;

use App\Enums\UserType;
use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Shop extends Model
{
    use HasFactory;
    use ModelEssentialsTrait;

    /**
     * Return products that this shop has.
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class)->withTimestamps();
    }

    /**
     * Return staffs working in a shop.
     */
    public function staffs(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the shop manager.
     */
    public function getManagerAttribute(): ?User
    {
        return $this->staffs()->whereType(UserType::SHOP_MANAGER)->first();
    }

    /**
     * Get shop orders relationship.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
