<?php

namespace App\Pages\Exceptions;

class PageNotFoundException extends PageException {

    public function __construct() {
        parent::__construct('Page was not found.');
    }

}
