<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteBySubdomainAndUrlQuery;
use App\Routes\RoutesFacade;
use Illuminate\Http\RedirectResponse;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class DispatchController extends Controller
{

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param PagesFacade $pagesFacade
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        PagesFacade $pagesFacade,
        RoutesFacade $routesFacade
    ) {
        $this->pagesFacade = $pagesFacade;
        $this->routesFacade = $routesFacade;
    }

    /**
     * @param string $subdomain
     * @param string $url
     * @return mixed
     *
     * @throws Throwable
     */
    public function show(string $subdomain, string $url)
    {
        try {
            $route = $this->routesFacade->queryOne(
                new GetRouteBySubdomainAndUrlQuery($subdomain, $url)
            );
        } catch (RouteNotFoundException $ex) {
            throw new NotFoundHttpException();
        }

        switch ($route->model_type) {
            case PageVariant::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return view('frontend.pages.pages.show', [
                    'renderedPageVariant' => $this->pagesFacade->render($route->model),
                ]);

            case Route::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return new RedirectResponse('/' . $route->model->url);

            default:
                throw new LogicException(
                    sprintf('Do not know how to dispatch route [model_type=%s].', $route->model_type)
                );
        }
    }

}
