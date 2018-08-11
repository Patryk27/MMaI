<?php

namespace App\Routes;

use App\Core\Exceptions\Exception as AppException;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Implementation\Repositories\RoutesRepositoryInterface;
use App\Routes\Implementation\Services\RoutesDeleter;
use App\Routes\Implementation\Services\RoutesQuerier;
use App\Routes\Implementation\Services\RoutesRerouter;
use App\Routes\Implementation\Services\RoutesValidator;
use App\Routes\Models\Route;
use App\Routes\Queries\RoutesQueryInterface;
use Illuminate\Support\Collection;

final class RoutesFacade
{

    /**
     * @var RoutesRepositoryInterface
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
     * @param RoutesRepositoryInterface $routesRepository
     * @param RoutesValidator $routesValidator
     * @param RoutesDeleter $routesDeleter
     * @param RoutesRerouter $routesRerouter
     * @param RoutesQuerier $routesQuerier
     */
    public function __construct(
        RoutesRepositoryInterface $routesRepository,
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
     * @param Route $route
     * @return void
     */
    public function delete(Route $route): void
    {
        $this->routesDeleter->delete($route);
    }

    /**
     * @param Route $oldRoute
     * @param Route $newRoute
     * @return void
     *
     * @throws AppException
     */
    public function reroute(Route $oldRoute, Route $newRoute): void
    {
        $this->routesValidator->validate($oldRoute);
        $this->routesValidator->validate($newRoute);

        $this->routesRerouter->reroute($oldRoute, $newRoute);
    }

    /**
     * @param Route $route
     * @return void
     *
     * @throws AppException
     */
    public function persist(Route $route): void
    {
        $this->routesValidator->validate($route);
        $this->routesRepository->persist($route);
    }

    /**
     * @param RoutesQueryInterface $query
     * @return Route
     *
     * @throws RouteNotFoundException
     */
    public function queryOne(RoutesQueryInterface $query): Route
    {
        $routes = $this->queryMany($query);

        if ($routes->isEmpty()) {
            throw new RouteNotFoundException('Route was not found.');
        }

        return $routes->first();
    }

    /**
     * @param RoutesQueryInterface $query
     * @return Collection|Route[]
     */
    public function queryMany(RoutesQueryInterface $query): Collection
    {
        return $this->routesQuerier->query($query);
    }

}