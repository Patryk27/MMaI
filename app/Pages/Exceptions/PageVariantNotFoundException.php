<?php

namespace App\Pages\Exceptions;

use App\Core\Exceptions\Exception;

class PageVariantNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct('Page variant was not found.');
    }

}