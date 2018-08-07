<?php

namespace App\Routes;

use App\Routes\Implementation\Repositories\RoutesRepositoryInterface;
use App\Routes\Implementation\Services\RoutesDeleter;
use App\Routes\Implementation\Services\RoutesQuerier;
use App\Routes\Implementation\Services\RoutesRerouter;
use App\Routes\Implementation\Services\RoutesValidator;

final class RoutesFactory
{

    /**
     * @param RoutesRepositoryInterface $routesRepository
     * @return RoutesFacade
     */
    public static function build(
        RoutesRepositoryInterface $routesRepository
    ): RoutesFacade {
        $routesValidator = new RoutesValidator();
        $routesDeleter = new RoutesDeleter($routesRepository);
        $routesRerouter = new RoutesRerouter($routesRepository);
        $routesQuerier = new RoutesQuerier($routesRepository);

        return new RoutesFacade(
            $routesRepository,
            $routesValidator,
            $routesDeleter,
            $routesRerouter,
            $routesQuerier
        );
    }

}