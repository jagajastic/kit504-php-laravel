<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

/**
 * Essential methods and properties used by models.
 */
trait ModelEssentialsTrait
{
    /**
     * Remove mass-assignments guard because we always validate data.
     *
     * @return array
     */
    public function getGuarded()
    {
        return [];
    }

    /**
     * Setup model event hooks.
     *
     * - Adds UUID to ID field for new item.
     */
    public static function bootModelEssentialsTrait()
    {
        static::creating(function (Model $model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
            }
        });
    }

    /**
     * Get model table name.
     */
    public static function table(): string
    {
        return \app(static::class)->getTable();
    }

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return \false;
    }

    /**
     * Specifies that the IDs on the table should be stored as strings.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }
}
