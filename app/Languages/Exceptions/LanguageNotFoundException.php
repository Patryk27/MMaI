<?php

namespace App\Languages\Exceptions;

class LanguageNotFoundException extends LanguageException
{

    public function __construct()
    {
        parent::__construct('Language was not found.');
    }

}