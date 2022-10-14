<?php

namespace App\Models;

use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;
    use ModelEssentialsTrait;

    /**
     * Bootstrap the model and its traits.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::deleted(function (Order $order) {
            $order->items()->delete();
        });
    }

    /**
     * Get order user relationship.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get shop where the order was placed.
     */
    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    /**
     * Get order items.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}
