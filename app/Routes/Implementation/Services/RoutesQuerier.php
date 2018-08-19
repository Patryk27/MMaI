<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Exceptions\RouteException;
use App\Routes\Implementation\Repositories\RoutesRepositoryInterface;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteBySubdomainAndUrlQuery;
use App\Routes\Queries\GetRoutesLikeUrlQuery;
use App\Routes\Queries\RoutesQueryInterface;
use Illuminate\Support\Collection;

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
     *
     * @throws RouteException
     */
    public function query(RoutesQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetRouteBySubdomainAndUrlQuery:
                return collect_one(
                    $this->routesRepository->getBySubdomainAndUrl(
                        $query->getSubdomain(),
                        $query->getUrl()
                    )
                );

            case $query instanceof GetRoutesLikeUrlQuery:
                return $this->routesRepository->getLikeUrl(
                    $query->getUrl()
                );

            default:
                throw new RouteException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

}