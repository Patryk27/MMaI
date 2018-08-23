<?php

namespace App\Application\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as TrimStringsBase;

class TrimStrings extends TrimStringsBase
{

    /**
     * @var string[]
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];

}