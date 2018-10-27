<?php

namespace App\Routes\Implementation\Repositories;

use App\Core\Models\Morphable;
use App\Routes\Models\Route;
use Illuminate\Support\Collection;

interface RoutesRepository
{

    /**
     * Returns route with given subdomain and URL, or `null` if no such route
     * exists.
     *
     * @param string $subdomain
     * @param string $url
     * @return Route|null
     */
    public function getBySubdomainAndUrl(string $subdomain, string $url): ?Route;

    /**
     * Returns all routes partially matching given URL.
     *
     * @param string $url
     * @return Collection|Route[]
     */
    public function getLikeUrl(string $url): Collection;

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
