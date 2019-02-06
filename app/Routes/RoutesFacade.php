<?php

namespace App\Routes;

use App\Routes\Exceptions\RouteException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Implementation\Services\RoutesCreator;
use App\Routes\Implementation\Services\RoutesDeleter;
use App\Routes\Implementation\Services\RoutesQuerier;
use App\Routes\Implementation\Services\RoutesRedirector;
use App\Routes\Implementation\Services\RoutesUpdater;
use App\Routes\Models\Route;
use App\Routes\Queries\RoutesQuery;
use App\Routes\Requests\CreateRoute;
use App\Routes\Requests\UpdateRoute;
use Illuminate\Support\Collection;

final class RoutesFacade {

    /** @var RoutesCreator */
    private $routesCreator;

    /** @var RoutesDeleter */
    private $routesDeleter;

    /** @var RoutesQuerier */
    private $routesQuerier;

    /** @var RoutesRedirector */
    private $routesRedirector;

    /** @var RoutesUpdater */
    private $routesUpdater;

    public function __construct(
        RoutesCreator $routesCreator,
        RoutesDeleter $routesDeleter,
        RoutesQuerier $routesQuerier,
        RoutesRedirector $routesRedirector,
        RoutesUpdater $routesUpdater
    ) {
        $this->routesCreator = $routesCreator;
        $this->routesDeleter = $routesDeleter;
        $this->routesQuerier = $routesQuerier;
        $this->routesRedirector = $routesRedirector;
        $this->routesUpdater = $routesUpdater;
    }

    /**
     * Creates a new route.
     *
     * @param CreateRoute $request
     * @return Route
     */
    public function create(CreateRoute $request): Route {
        return $this->routesCreator->create($request);
    }

    /**
     * Updates an already existing route.
     *
     * @param Route $route
     * @param UpdateRoute $request
     * @return void
     */
    public function update(Route $route, UpdateRoute $request): void {
        $this->routesUpdater->update($route, $request);
    }

    /**
     * Deletes given route.
     *
     * @param Route $route
     * @return void
     */
    public function delete(Route $route): void {
        $this->routesDeleter->delete($route);
    }

    /**
     * Redirects given $from so that from now on it points onto $to.
     *
     * @param Route $from
     * @param Route $to
     * @return void
     * @throws RouteException
     */
    public function redirect(Route $from, Route $to): void {
        $this->routesRedirector->redirect($from, $to);
    }

    /**
     * Returns the first route matching given query.
     * Throws an exception if no such route exists.
     *
     * @param RoutesQuery $query
     * @return Route
     * @throws RouteException
     * @throws RouteNotFoundException
     */
    public function queryOne(RoutesQuery $query): Route {
        $routes = $this->queryMany($query);

        if ($routes->isEmpty()) {
            throw new RouteNotFoundException();
        }

        return $routes->first();
    }

    /**
     * Returns all routes matching given query.
     *
     * @param RoutesQuery $query
     * @return Collection|Route[]
     * @throws RouteException
     */
    public function queryMany(RoutesQuery $query): Collection {
        return $this->routesQuerier->query($query);
    }

}
