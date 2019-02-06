<?php

namespace App\Routes;

use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Implementation\Services\RoutesCreator;
use App\Routes\Implementation\Services\RoutesDeleter;
use App\Routes\Implementation\Services\RoutesQuerier;
use App\Routes\Implementation\Services\RoutesRedirector;
use App\Routes\Implementation\Services\RoutesUpdater;

final class RoutesFactory {

    public static function build(
        RoutesRepository $routesRepository
    ): RoutesFacade {
        $routesCreator = new RoutesCreator($routesRepository);
        $routesDeleter = new RoutesDeleter($routesRepository);
        $routesQuerier = new RoutesQuerier($routesRepository);
        $routesRedirector = new RoutesRedirector($routesRepository);
        $routesUpdater = new RoutesUpdater($routesRepository);

        return new RoutesFacade(
            $routesCreator,
            $routesDeleter,
            $routesQuerier,
            $routesRedirector,
            $routesUpdater
        );
    }

}
