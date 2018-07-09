<?php

namespace App\CommandBus\Commands\Routes;

use App\Models\Route;

class DeleteCommand
{

    /**
     * @var Route
     */
    private $route;

    /**
     * @param Route $route
     */
    public function __construct(
        Route $route
    ) {
        $this->route = $route;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

}