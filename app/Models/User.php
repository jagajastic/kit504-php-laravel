<?php

namespace App\Models;

use App\Traits\AuthModelTrait;
use App\Traits\ModelEssentialsTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
}
