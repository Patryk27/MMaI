<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Implementation\Repositories\RoutesRepository;
use App\Routes\Models\Route;
use App\Routes\Requests\CreateRoute;

class RoutesCreator {

    /** @var RoutesRepository */
    private $routesRepository;

    public function __construct(RoutesRepository $routesRepository) {
        $this->routesRepository = $routesRepository;
    }

    /**
     * @param CreateRoute $request
     * @return Route
     */
    public function create(CreateRoute $request): Route {
        $route = new Route([
            'subdomain' => $request->get('subdomain'),
            'url' => $request->get('url'),
            'model_type' => $request->get('model_type'),
            'model_id' => $request->get('model_id'),
        ]);

        $this->routesRepository->persist($route);

        return $route;
    }

}
