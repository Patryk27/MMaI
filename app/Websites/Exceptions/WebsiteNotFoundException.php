<?php

namespace App\Websites\Exceptions;

class WebsiteNotFoundException extends WebsiteException {

    public function __construct() {
        parent::__construct('Website was not found.');
    }

}
