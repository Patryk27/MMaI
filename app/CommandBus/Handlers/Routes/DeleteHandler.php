<?php

namespace App\CommandBus\Handlers\Routes;

use App\CommandBus\Commands\Routes\DeleteCommand;
use App\Models\Route;
use App\Repositories\RoutesRepository;
use Illuminate\Database\Connection as DatabaseConnection;
use Throwable;

class DeleteHandler
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
     * @param DeleteCommand $command
     * @throws Throwable
     */
    public function handle(DeleteCommand $command): void
    {
        $this->db->transaction(function () use ($command): void {
            $this->delete(
                $command->getRoute()
            );
        });
    }

    /**
     * @param Route $route
     * @return void
     *
     * @throws Throwable
     */
    private function delete(Route $route): void
    {
        // Find and remove all the dependent-routes first - it functions as "on
        // delete cascade" constraint, which, sadly, cannot be created
        // automatically when dealing with polymorphic relationships.
        $this->routesRepository
            ->getPointingAt($route)
            ->each(function (Route $route): void {
                $this->delete($route);
            });

        $this->routesRepository->delete($route);
    }

}