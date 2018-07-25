<?php

namespace App\Routes\Internal\Services;

use App\Routes\Internal\Repositories\RoutesRepositoryInterface;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteByUrlQuery;
use App\Routes\Queries\RouteQueryInterface;
use Illuminate\Support\Collection;
use LogicException;

class RoutesQuerier
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
     * @param RouteQueryInterface $query
     * @return Collection
     */
    public function query(RouteQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetRouteByUrlQuery:
                return $this->createCollectionForOne(
                    $this->routesRepository->getByUrl(
                        $query->getUrl()
                    )
                );

            default:
                throw new LogicException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * @param Route|null $route
     * @return Collection
     */
    private function createCollectionForOne(?Route $route): Collection
    {
        $collection = new Collection();

        if (isset($route)) {
            $collection->push($route);
        }

        return $collection;
    }

}