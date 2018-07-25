<?php

namespace App\App\Http\Controllers\Frontend;

use App\App\Http\Controllers\Controller;
use App\Routes\Queries\GetRouteByUrlQuery;
use App\Routes\RoutesFacade;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DispatchController extends Controller
{

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        RoutesFacade $routesFacade
    ) {
        $this->routesFacade = $routesFacade;
    }

    /**
     * @param string|null $url
     * @return mixed
     *
     * @throws Exception
     */
    public function show(?string $url = null)
    {
        $routes = $this->routesFacade->queryMany(
            new GetRouteByUrlQuery(empty($url) ? '/' : $url)
        );

        if ($routes->isEmpty()) {
            throw new NotFoundHttpException();
        }

        return $this->routesFacade->dispatch(
            $routes->first()
        );
    }

}