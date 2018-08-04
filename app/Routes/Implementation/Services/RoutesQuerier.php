<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Implementation\Repositories\RoutesRepositoryInterface;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteByUrlQuery;
use App\Routes\Queries\RoutesQueryInterface;
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
     * @param RoutesQueryInterface $query
     * @return Collection|Route[]
     */
    public function query(RoutesQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetRouteByUrlQuery:
                return $this->createCollectionForNullable(
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
    private function createCollectionForNullable(?Route $route): Collection
    {
        $collection = new Collection();

        if (isset($route)) {
            $collection->push($route);
        }

        return $collection;
    }

}