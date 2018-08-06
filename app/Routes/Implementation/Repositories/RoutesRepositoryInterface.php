<?php

namespace App\Routes\Implementation\Repositories;

use App\Core\Models\Interfaces\Morphable;
use App\Routes\Models\Route;
use Illuminate\Support\Collection;

interface RoutesRepositoryInterface
{

    /**
     * Returns route with given URL or `null` if no such route exists.
     *
     * @param string $url
     * @return Route|null
     */
    public function getByUrl(string $url): ?Route;

    /**
     * Returns all routes that point at given morphable.
     *
     * @param Morphable $morphable
     * @return Collection|Route[]
     */
    public function getPointingAt(Morphable $morphable): Collection;

    /**
     * Saves given route in the database.
     *
     * @param Route $route
     * @return void
     */
    public function persist(Route $route): void;

    /**
     * Removes given route from the database.
     *
     * @param Route $route
     * @return void
     *
     * @see \App\Routes\Implementation\Services\RoutesDeleter
     */
    public function delete(Route $route): void;

}