<?php

namespace App\Tags\Exceptions;

use App\Core\Exceptions\Exception;

class TagNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct('Tag was not found.');
    }

}