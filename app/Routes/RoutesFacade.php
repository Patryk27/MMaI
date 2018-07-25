<?php

namespace App\Routes;

use App\Core\Exceptions\Exception;
use App\Routes\Internal\Services\RoutesDeleter;
use App\Routes\Internal\Services\RoutesDispatcher;
use App\Routes\Internal\Services\RoutesQuerier;
use App\Routes\Internal\Services\RoutesRerouter;
use App\Routes\Models\Route;
use App\Routes\Queries\RouteQueryInterface;
use Illuminate\Support\Collection;
use Throwable;

class RoutesFacade
{

    /**
     * @var RoutesDeleter
     */
    private $routesDeleter;

    /**
     * @var RoutesDispatcher
     */
    private $routesDispatcher;

    /**
     * @var RoutesRerouter
     */
    private $routesRerouter;

    /**
     * @var RoutesQuerier
     */
    private $routesQuerier;

    /**
     * @param RoutesDeleter $routesDeleter
     * @param RoutesDispatcher $routesDispatcher
     * @param RoutesRerouter $routesRerouter
     * @param RoutesQuerier $routesQuerier
     */
    public function __construct(
        RoutesDeleter $routesDeleter,
        RoutesDispatcher $routesDispatcher,
        RoutesRerouter $routesRerouter,
        RoutesQuerier $routesQuerier
    ) {
        $this->routesDeleter = $routesDeleter;
        $this->routesDispatcher = $routesDispatcher;
        $this->routesRerouter = $routesRerouter;
        $this->routesQuerier = $routesQuerier;
    }

    /**
     * @param Route $route
     * @return void
     */
    public function delete(Route $route): void
    {
        $this->routesDeleter->delete($route);
    }

    /**
     * @param Route $route
     * @return mixed
     *
     * @throws Throwable
     */
    public function dispatch(Route $route)
    {
        return $this->routesDispatcher->dispatch($route);
    }

    /**
     * @param Route $oldRoute
     * @param Route $newRoute
     * @return void
     */
    public function reroute(Route $oldRoute, Route $newRoute): void
    {
        $this->routesRerouter->reroute($oldRoute, $newRoute);
    }

    /**
     * @param RouteQueryInterface $query
     * @return Route
     *
     * @throws Exception
     */
    public function queryOne(RouteQueryInterface $query): Route
    {
        $routes = $this->queryMany($query);

        if ($routes->isEmpty()) {
            throw new Exception('Route was not found.');
        }

        return $routes->first();
    }

    /**
     * @param RouteQueryInterface $query
     * @return Collection|Route[]
     */
    public function queryMany(RouteQueryInterface $query): Collection
    {
        return $this->routesQuerier->query($query);
    }

}