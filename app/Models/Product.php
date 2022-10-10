<?php

namespace App\Models;

use App\Casts\FileCast;
use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;
    use ModelEssentialsTrait;

    /**
     * The attributes that should be casted.
     *
     * @var array<string, mixed>
     */
    protected $casts = [
        'image' => FileCast::class . ':product-images',
    ];

    /**
     * Return shops that has this product.
     */
    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(Shop::class)->withTimestamps();
    }
}
