<?php

namespace App\Models;

use App\Traits\AuthModelTrait;
use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;

class User extends Model
{
    use HasFactory;
    use AuthModelTrait;
    use ModelEssentialsTrait;

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Returns the user's account balance in USD.
     */
    public function getAccountBalanceUsdAttribute(): float
    {
        return $this->account_balance / 100;
    }

    /**
     * Return shop that a user is working in.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get user's orders relationship.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Generate cart cache key.
     */
    public function generateCartKey(string $shopId): string
    {
        return 'cart:' . $this->getKey() . ':' . $shopId;
    }

    /**
     * Get user cart for shop.
     */
    public function getCart(string $shopId): array
    {
        return Cache::rememberForever(
            $this->generateCartKey($shopId),
            function () {
                return [];
            }
        );
    }

    /**
     * Get user cart for shop.
     */
    public function setCart(string $shopId, array $data): bool
    {
        return Cache::forever(
            $this->generateCartKey($shopId),
            $data,
        );
    }
}
