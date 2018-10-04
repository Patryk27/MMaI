<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteBySubdomainAndUrlQuery;
use App\Routes\RoutesFacade;
use Exception;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class DispatchController extends Controller
{

    /**
     * @var GateContract
     */
    private $gate;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param GateContract $gate
     * @param PagesFacade $pagesFacade
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        GateContract $gate,
        PagesFacade $pagesFacade,
        RoutesFacade $routesFacade
    ) {
        $this->gate = $gate;
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
    public function dispatch(string $subdomain, string $url)
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
                return $this->dispatchPageVariant($route->model);

            case Route::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->dispatchRoute($route->model);

            default:
                throw new LogicException(
                    sprintf('Do not know how to dispatch route [model_type=%s].', $route->model_type)
                );
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @return ViewContract
     *
     * @throws NotFoundHttpException
     * @throws Exception
     */
    private function dispatchPageVariant(PageVariant $pageVariant): ViewContract
    {
        if ($this->gate->denies('show', [$pageVariant])) {
            throw new NotFoundHttpException();
        }

        return view('frontend.pages.pages.show', [
            'renderedPageVariant' => $this->pagesFacade->render($pageVariant),
        ]);
    }

    /**
     * @param Route $route
     * @return RedirectResponse
     */
    private function dispatchRoute(Route $route): RedirectResponse
    {
        return new RedirectResponse(
            $route->getTargetUrl()
        );
    }

}
