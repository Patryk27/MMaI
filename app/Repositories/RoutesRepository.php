<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Interfaces\Morphable;
use App\Models\Route;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Throwable;

class RoutesRepository
{

    /**
     * @var GenericRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(
            new Route()
        );
    }

    /**
     * Returns route with given URL or `null` if no such route exists.
     *
     * @param string $url
     * @return Route|null
     */
    public function getByUrl(string $url): ?Route
    {
        return $this->repository->getBy('url', $url);
    }

    /**
     * Returns route with given URL or throws an exception if no such route
     * exists.
     *
     * @param string $url
     * @return Route
     *
     * @throws RepositoryException
     */
    public function getByUrlOrFail(string $url): Route
    {
        return $this->repository->getByOrFail('url', $url);
    }

    /**
     * Returns all routes that point at given morphable.
     *
     * @param Morphable $morphable
     * @return EloquentCollection|Route[]
     */
    public function getPointingAt(Morphable $morphable): EloquentCollection
    {
        $stmt = (new Route)->newQuery();
        $stmt->where([
            'model_id' => $morphable->getMorphableId(),
            'model_type' => $morphable::getMorphableType(),
        ]);

        return $stmt->get();
    }

    /**
     * Saves given route in the database.
     *
     * Does NOT automatically perform any rerouting - if you need such, please
     * use @see \App\CommandBus\Commands\Routes\RerouteCommand.
     *
     * @param Route $route
     * @return void
     *
     * @throws Throwable
     */
    public function persist(Route $route): void
    {
        $this->repository->persist($route);
    }

    /**
     * Removes given route from the database.
     *
     * Does NOT automatically remove any dependent-route - for complex removal,
     * please use @see \App\CommandBus\Commands\Routes\DeleteCommand.
     *
     * @param Route $route
     * @return void
     *
     * @throws Throwable
     */
    public function delete(Route $route): void
    {
        $this->repository->delete($route);
    }

}