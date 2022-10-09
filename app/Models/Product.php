<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    /**
     * Return shops that has this product.
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class)->withTimestamps();
    }
}
