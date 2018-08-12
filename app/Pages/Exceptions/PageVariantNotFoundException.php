<?php

namespace App\Pages\Exceptions;

class PageVariantNotFoundException extends PageException
{

    public function __construct()
    {
        parent::__construct('Page variant was not found.');
    }

}