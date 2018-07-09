<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * This model describes a single user.
 *
 * There's no "status" or "role" for now, because basically every user is an
 * active administrator.
 *
 * -----
 *
 * @property-read int $id
 * @property string $login
 * @property string $name
 * @property string $password
 * @property string $remember_token
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class User extends Authenticatable
{

    /**
     * @var string[]
     */
    protected $fillable = [
        'login',
        'name',
        'password',
        'remember_token',
    ];

}
