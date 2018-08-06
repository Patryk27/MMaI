<?php

namespace App\Languages\Exceptions;

use App\Core\Exceptions\Exception;

class LanguageNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct('Language was not found.');
    }

}