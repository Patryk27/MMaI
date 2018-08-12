<?php

namespace App\Routes\Implementation\Services;

use App\Routes\Exceptions\RouteException;
use App\Routes\Models\Route;

class RoutesValidator
{

    /**
     * @param Route $route
     * @return void
     *
     * @throws RouteException
     */
    public function validate(Route $route): void
    {
        if (starts_with($route->url, '/')) {
            throw new RouteException('Route must not start with [/].');
        }

        if ($route->model_id === null || $route->model_type === null) {
            throw new RouteException('Route must point at a model.');
        }
    }

}