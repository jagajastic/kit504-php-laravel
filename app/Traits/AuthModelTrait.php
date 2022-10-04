<?php

namespace App\Traits;

use App\Models\ApiToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait AuthModelTrait
{
    /**
     * Get user by credentials.
     */
    public static function getUserBy(string $email, string $password)
    {
        $user = static::whereEmail($email)->first();

        if ($user === \null) {
            return \null;
        }

        if (!Hash::check($password, $user->password)) {
            return \null;
        }

        return $user;
    }

    /**
     * Set the password attribute.
     *
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute(string $value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    /**
     * Get all of the user's API tokens.
     */
    public function apiTokens(): MorphMany
    {
        return $this->morphMany(ApiToken::class, 'user');
    }

    /**
     * Returns the attributes that should be hidden for serialization.
     */
    public function hidden()
    {
        return \array_merge(['password'], $this->hidden);
    }
}
