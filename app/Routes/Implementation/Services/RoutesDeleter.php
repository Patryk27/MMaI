<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Implementation\Repositories\RoutesRepositoryInterface;
use App\Routes\Models\Route;

/**
 * @see \Tests\Unit\Routes\DeleteTest
 */
class RoutesDeleter
{

    /**
     * @var RoutesRepositoryInterface
     */
    private $routesRepository;

    /**
     * @param RoutesRepositoryInterface $routesRepository
     */
    public function __construct(
        RoutesRepositoryInterface $routesRepository
    ) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param Route $route
     * @return void
     *
     * @todo utilize transactions
     */
    public function delete(Route $route): void
    {
        $parentRoutes = $this->routesRepository->getPointingAt($route);

        foreach ($parentRoutes as $parentRoute) {
            $parentRoute->setPointsAt($route->model);
            $this->routesRepository->persist($parentRoute);
        }

        $this->routesRepository->delete($route);
    }

}