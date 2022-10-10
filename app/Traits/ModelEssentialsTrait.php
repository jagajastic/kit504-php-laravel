<?php

namespace App\Traits;

use App\Casts\FileCast;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Throwable;

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
     * - Deletes a model attached files after deleting the model.
     */
    public static function bootModelEssentialsTrait()
    {
        static::creating(function (Model $model) {
            if (!$model->getKey()) {
                $model->{$model->getKeyName()} = Str::orderedUuid()->toString();
            }
        });

        static::deleted(function (Model $model) {
            try {
                foreach ($model->getCasts() as $key => $value) {
                    if (Str::startsWith($value, FileCast::class)) {
                        $args = \explode(':', $value);

                        if (\count($args) === 2) {
                            $args = \explode(',', $args[1]);

                            $fileCast = new FileCast(...$args);

                            $fileCast->deleteOldFileIfExists(
                                $model->getAttributes(),
                                $key
                            );
                        }
                    }
                }
            } catch (Throwable $e) {
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

    /**
     * Scope a query to add pagination.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  int|null  $perPage
     * @param string $pageParam
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePaginated(
        $query,
        $perPage = \null,
        $pageParam = 'page',
        $columns = ['*']
    ) {
        if ($perPage === \null) {
            $perPage = \intval(\request('per_page'));
            $perPage = $perPage === 0 ? 10 : $perPage;
        }

        return $query->simplePaginate($perPage, $columns, $pageParam);
    }
}
