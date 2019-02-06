<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Exceptions\RouteException;
use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Models\Route;

class RoutesRedirector {

    /** @var RoutesRepository */
    private $routesRepository;

    public function __construct(RoutesRepository $routesRepository) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param Route $from
     * @param Route $to
     * @return void
     * @throws RouteException
     *
     * @todo utilize transactions / move to repository
     */
    public function redirect(Route $from, Route $to): void {
        if (!$from->exists || !$to->exists) {
            throw new RouteException('Cannot re-route non-existing routes.');
        }

        $from->setPointsAt($to);

        $this->routesRepository->persist($from);
        $this->routesRepository
            ->getPointingAt($from)
            ->each(function (Route $route) use ($to): void {
                $this->redirect($route, $to);
            });
    }

}
