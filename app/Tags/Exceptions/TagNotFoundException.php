<?php

namespace App\Tags\Exceptions;

class TagNotFoundException extends TagException
{

    public function __construct()
    {
        parent::__construct('Tag was not found.');
    }

}