<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Exceptions\RouteException;
use App\Routes\Implementation\Repositories\RoutesRepositoryInterface;
use App\Routes\Models\Route;

/**
 * @see \Tests\Unit\Routes\RerouteTest
 */
class RoutesRerouter
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
     * @param Route $oldRoute
     * @param Route $newRoute
     * @return void
     *
     * @throws RouteException
     *
     * @todo utilize transactions / move to repository
     */
    public function reroute(Route $oldRoute, Route $newRoute): void
    {
        if (!$oldRoute->exists || !$newRoute->exists) {
            throw new RouteException('Cannot re-route non-existing routes.');
        }

        $oldRoute->setPointsAt($newRoute);
        $this->routesRepository->persist($oldRoute);

        $this->routesRepository
            ->getPointingAt($oldRoute)
            ->each(function (Route $route) use ($newRoute): void {
                $this->reroute($route, $newRoute);
            });
    }

}