<?php

namespace App\Routes\Exceptions;

class RouteNotFoundException extends RouteException {

    public function __construct() {
        parent::__construct('Route was not found.');
    }

}
