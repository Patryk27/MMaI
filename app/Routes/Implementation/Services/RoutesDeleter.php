<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Models\Route;

class RoutesDeleter {
    /** @var RoutesRepository */
    private $routesRepository;

    public function __construct(RoutesRepository $routesRepository) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param Route $route
     * @return void
     *
     * @todo utilize transactions
     */
    public function delete(Route $route): void {
        $parentRoutes = $this->routesRepository->getPointingAt($route);

        foreach ($parentRoutes as $parentRoute) {
            $parentRoute->setPointsAt($route->model);
            $this->routesRepository->persist($parentRoute);
        }

        $this->routesRepository->delete($route);
    }
}
