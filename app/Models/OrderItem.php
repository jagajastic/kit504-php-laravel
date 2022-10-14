<?php

namespace App\Models;

use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;
    use ModelEssentialsTrait;

    /**
     * Get order relationship.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
