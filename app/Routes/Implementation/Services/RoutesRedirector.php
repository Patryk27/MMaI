<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Exceptions\RouteException;
use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Models\Route;

class RoutesRedirector {

    private const MAX_DEPTH = 50; // chosen arbitrarily

    /** @var RoutesRepository */
    private $routesRepository;

    public function __construct(RoutesRepository $routesRepository) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param Route $from
     * @param Route $to
     * @return void
     */
    public function redirect(Route $from, Route $to): void {
        $this->routesRepository->transaction(function () use ($from, $to): void {
            $this->redirectEx($from, $to, 0);
        });
    }

    /**
     * @param Route $from
     * @param Route $to
     * @param int $depth
     * @return void
     * @throws RouteException
     */
    private function redirectEx(Route $from, Route $to, int $depth): void {
        if (!$from->exists || !$to->exists) {
            throw new RouteException('Cannot re-route non-existing routes.');
        }

        if ($depth > self::MAX_DEPTH) {
            throw new RouteException('Reached limit of redirections - it seems like you might have a cycle in your route graph.');
        }

        $from->setModel($to);
        $this->routesRepository->persist($from);

//        foreach ($this->routesRepository->getPointingAt($from) as $from) {
//            $this->redirectEx($from, $to);
//        }
    }
}
