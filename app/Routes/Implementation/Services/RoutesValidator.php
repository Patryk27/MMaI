<?php

namespace App\Routes\Implementation\Services;

use App\Core\Exceptions\Exception as AppException;
use App\Routes\Models\Route;

class RoutesValidator
{

    /**
     * @param Route $route
     * @return void
     *
     * @throws AppException
     */
    public function validate(Route $route): void
    {
        if (starts_with($route->url, '/')) {
            throw new AppException('Route must not start with [/].');
        }

        if ($route->model_id === null || $route->model_type === null) {
            throw new AppException('Route must point at a model.');
        }
    }

}