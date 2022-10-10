<?php

namespace App\Casts;

use Illuminate\Support\Arr;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class FileCast implements CastsAttributes
{
    /**
     * The folder to store the file to.
     */
    protected string $folder;

    /**
     * The storage disk.
     */
    protected string $disk;

    /**
     * Create a new cast class instance.
     */
    public function __construct(string $folder, ?string $disk = 'public')
    {
        $this->disk   = $disk;
        $this->folder = $folder;
    }

    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function get($model, $key, $value, $attributes)
    {
        return $value === null ? \null : $this->getStorage()->url($value);
    }

    /**
     * Prepare the given value for storage.
     * Set the [$value] to [null] to remove the file and attribute.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        // Check if `$value` is not null and it's an uploaded file...
        if ($value !== \null && !$value instanceof UploadedFile) {
            throw new \InvalidArgumentException(
                'The given value is not an uploaded file.',
            );
        }

        // Delete old file if it exists.
        $this->deleteOldFileIfExists($attributes, $key);

        // Store the file if not null and return the path...
        return $value === \null
            ? \null
            : $this->getStorage()->putFile($this->folder, $value);
    }

    /**
     * Get storage disk driver instance.
     */
    protected function getStorage(): Filesystem
    {
        return Storage::disk($this->disk);
    }

    /**
     * Delete old file if it exists.
     */
    public function deleteOldFileIfExists($attributes, $key)
    {
        if (\is_string($oldFile = Arr::get($attributes, $key))) {
            if (!\preg_match('/^predefined/', $oldFile)) {
                $this->getStorage()->delete($oldFile);
            }
        }
    }
}
