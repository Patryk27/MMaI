<?php

namespace App\Routes;

use App\Routes\Internal\Repositories\RoutesRepositoryInterface;
use App\Routes\Internal\Services\RoutesDeleter;
use App\Routes\Internal\Services\RoutesDispatcher;
use App\Routes\Internal\Services\RoutesQuerier;
use App\Routes\Internal\Services\RoutesRerouter;

class RoutesFactory
{

    /**
     * @param RoutesRepositoryInterface $routesRepository
     * @return RoutesFacade
     */
    public static function build(
        RoutesRepositoryInterface $routesRepository
    ): RoutesFacade {
        $routesDeleter = new RoutesDeleter($routesRepository);
        $routesDispatcher = new RoutesDispatcher();
        $routesRerouter = new RoutesRerouter($routesRepository);
        $routesQuerier = new RoutesQuerier($routesRepository);

        return new RoutesFacade(
            $routesDeleter,
            $routesDispatcher,
            $routesRerouter,
            $routesQuerier
        );
    }

}