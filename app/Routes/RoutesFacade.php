<?php

namespace App\Routes;

use App\Routes\Exceptions\RouteException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Implementation\Services\RoutesDeleter;
use App\Routes\Implementation\Services\RoutesQuerier;
use App\Routes\Implementation\Services\RoutesRerouter;
use App\Routes\Implementation\Services\RoutesValidator;
use App\Routes\Models\Route;
use App\Routes\Queries\RoutesQuery;
use Illuminate\Support\Collection;

final class RoutesFacade
{

    /**
     * @var RoutesRepository
     */
    private $routesRepository;

    /**
     * @var RoutesValidator
     */
    private $routesValidator;

    /**
     * @var RoutesDeleter
     */
    private $routesDeleter;

    /**
     * @var RoutesRerouter
     */
    private $routesRerouter;

    /**
     * @var RoutesQuerier
     */
    private $routesQuerier;

    /**
     * @param RoutesRepository $routesRepository
     * @param RoutesValidator $routesValidator
     * @param RoutesDeleter $routesDeleter
     * @param RoutesRerouter $routesRerouter
     * @param RoutesQuerier $routesQuerier
     */
    public function __construct(
        RoutesRepository $routesRepository,
        RoutesValidator $routesValidator,
        RoutesDeleter $routesDeleter,
        RoutesRerouter $routesRerouter,
        RoutesQuerier $routesQuerier
    ) {
        $this->routesRepository = $routesRepository;
        $this->routesValidator = $routesValidator;
        $this->routesDeleter = $routesDeleter;
        $this->routesRerouter = $routesRerouter;
        $this->routesQuerier = $routesQuerier;
    }

    /**
     * Deletes given route.
     *
     * @param Route $route
     * @return void
     *
     * @see \Tests\Unit\Routes\DeleteTest
     */
    public function delete(Route $route): void
    {
        $this->routesDeleter->delete($route);
    }

    /**
     * Re-routes given route.
     *
     * @param Route $oldRoute
     * @param Route $newRoute
     * @return void
     *
     * @throws RouteException
     *
     * @see \Tests\Unit\Routes\RerouteTest
     */
    public function reroute(Route $oldRoute, Route $newRoute): void
    {
        $this->routesValidator->validate($oldRoute);
        $this->routesValidator->validate($newRoute);

        $this->routesRerouter->reroute($oldRoute, $newRoute);
    }

    /**
     * Returns the first route matching given query.
     * Throws an exception if no such route exists.
     *
     * @param RoutesQuery $query
     * @return Route
     *
     * @throws RouteException
     * @throws RouteNotFoundException
     */
    public function queryOne(RoutesQuery $query): Route
    {
        $routes = $this->queryMany($query);

        if ($routes->isEmpty()) {
            throw new RouteNotFoundException('Route was not found.');
        }

        return $routes->first();
    }

    /**
     * Returns all routes matching given query.
     *
     * @param RoutesQuery $query
     * @return Collection|Route[]
     *
     * @throws RouteException
     */
    public function queryMany(RoutesQuery $query): Collection
    {
        return $this->routesQuerier->query($query);
    }

}
