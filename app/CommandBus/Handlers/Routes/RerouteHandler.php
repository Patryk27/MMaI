<?php

namespace App\CommandBus\Handlers\Routes;

use App\CommandBus\Commands\Routes\RerouteCommand;
use App\Models\Route;
use App\Repositories\RoutesRepository;
use Illuminate\Database\Connection as DatabaseConnection;
use Throwable;

class RerouteHandler
{

    /**
     * @var DatabaseConnection
     */
    private $db;

    /**
     * @var RoutesRepository
     */
    private $routesRepository;

    /**
     * @param DatabaseConnection $db
     * @param RoutesRepository $routesRepository
     */
    public function __construct(
        DatabaseConnection $db,
        RoutesRepository $routesRepository
    ) {
        $this->db = $db;
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param RerouteCommand $command
     * @return void
     *
     * @throws Throwable
     */
    public function handle(RerouteCommand $command): void
    {
        $this->db->transaction(function () use ($command): void {
            $this->reroute(
                $command->getOldRoute(),
                $command->getNewRoute()
            );
        });
    }

    /**
     * @param Route $oldRoute
     * @param Route $newRoute
     * @return void
     *
     * @throws Throwable
     */
    private function reroute(Route $oldRoute, Route $newRoute): void
    {
        // If we've received a not-existing route, we need to save it before
        // proceeding - we'll need its id later.
        if (!$newRoute->exists) {
            $this->routesRepository->persist($newRoute);
        }

        // Step 1: update old route so that it points at the new one
        $oldRoute->setPointsAt($newRoute);

        $this->routesRepository->persist($oldRoute);

        // Step 2: re-route all the routes which previously pointed at the old
        // route, to point at the new one.
        //
        // Notice we're doing recursion here, because there may be more than one
        // level of indirection between routes (changing one route might require
        // updating some other ones).
        $this->routesRepository
            ->getPointingAt($oldRoute)
            ->each(function (Route $route) use ($newRoute): void {
                $this->reroute($route, $newRoute);
            });
    }

}