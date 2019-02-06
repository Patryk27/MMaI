<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Models\Route;
use App\Routes\Requests\UpdateRoute;

class RoutesUpdater {

    /** @var RoutesRepository */
    private $routesRepository;

    public function __construct(RoutesRepository $routesRepository) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param Route $route
     * @param UpdateRoute $request
     * @return void
     */
    public function update(Route $route, UpdateRoute $request): void {
        $route->fill([
            'subdomain' => $request->get('subdomain'),
            'url' => $request->get('url'),
            'model_type' => $request->get('model_type'),
            'model_id' => $request->get('model_type'),
        ]);

        $this->routesRepository->persist($route);
    }

}
