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

    protected $hidden = [
        'password',
    ];
}
