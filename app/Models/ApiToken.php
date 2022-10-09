<?php

namespace App\Models;

use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ApiToken extends Model
{
    use HasFactory;
    use ModelEssentialsTrait;

    /**
     * The attributes that should be casted.
     *
     * @var array<string, mixed>
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the parent user model.
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Set hashed value attribute.
     */
    public function setValueAttribute(string $value): void
    {
        $this->attributes['value'] = static::hashString($value);
    }

    /**
     * Generate a hashed token.
     */
    public static function hashString(string $value): string
    {
        $hashed = \hash('sha256', ($value . '-' . \config('app.key')));

        return \is_string($hashed) ? $hashed : '';
    }
}
