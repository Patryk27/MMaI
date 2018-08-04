<?php

namespace App\Menus\Exceptions;

use App\Core\Exceptions\Exception;

class MenuItemNotFoundException extends Exception
{

    public function __construct()
    {
        parent::__construct('Menu item was not found.');
    }

}