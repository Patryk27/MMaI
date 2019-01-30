<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Exceptions\RouteException;
use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteBySubdomainAndUrl;
use App\Routes\Queries\GetRoutesLikeUrl;
use App\Routes\Queries\RoutesQuery;
use Illuminate\Support\Collection;

class RoutesQuerier {

    /** @var RoutesRepository */
    private $routesRepository;

    public function __construct(RoutesRepository $routesRepository) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param RoutesQuery $query
     * @return Collection|Route[]
     * @throws RouteException
     */
    public function query(RoutesQuery $query): Collection {
        switch (true) {
            case $query instanceof GetRouteBySubdomainAndUrl:
                return collect_one(
                    $this->routesRepository->getBySubdomainAndUrl(
                        $query->getSubdomain(),
                        $query->getUrl()
                    )
                );

            case $query instanceof GetRoutesLikeUrl:
                return $this->routesRepository->getLikeUrl(
                    $query->getUrl()
                );

            default:
                throw new RouteException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

}
