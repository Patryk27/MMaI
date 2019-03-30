<?php

namespace App\Application\Interfaces\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as BaseTrimStrings;

final class TrimStrings extends BaseTrimStrings {

    /** @var string[] */
    protected $except = [
        'password',
        'password_confirmation',
    ];

}
