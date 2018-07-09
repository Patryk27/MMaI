<?php

namespace App\CommandBus\Commands\Routes;

use App\Models\Route;

class RerouteCommand
{

    /**
     * @var Route
     */
    private $oldRoute;

    /**
     * @var Route
     */
    private $newRoute;

    /**
     * @param Route $oldRoute
     * @param Route $newRoute
     */
    public function __construct(
        Route $oldRoute,
        Route $newRoute
    ) {
        $this->oldRoute = $oldRoute;
        $this->newRoute = $newRoute;
    }

    /**
     * @return Route
     */
    public function getOldRoute(): Route
    {
        return $this->oldRoute;
    }

    /**
     * @return Route
     */
    public function getNewRoute(): Route
    {
        return $this->newRoute;
    }

}