<?php

namespace App\Routes;

use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Implementation\Services\RoutesDeleter;
use App\Routes\Implementation\Services\RoutesQuerier;
use App\Routes\Implementation\Services\RoutesRerouter;
use App\Routes\Implementation\Services\RoutesValidator;

final class RoutesFactory {
    /**
     * Builds an instance of @see RoutesFacade.
     *
     * @param RoutesRepository $routesRepository
     * @return RoutesFacade
     */
    public static function build(
        RoutesRepository $routesRepository
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
