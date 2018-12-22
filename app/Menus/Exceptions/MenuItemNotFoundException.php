<?php

namespace App\Menus\Exceptions;

class MenuItemNotFoundException extends MenuException
{
    public function __construct()
    {
        parent::__construct('Menu item was not found.');
    }
}
