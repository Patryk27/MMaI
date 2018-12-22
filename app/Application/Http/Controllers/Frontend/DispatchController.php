<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteBySubdomainAndUrlQuery;
use App\Routes\RoutesFacade;
use Exception;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class DispatchController extends Controller
{
    /** @var GateContract */
    private $gate;

    /** @var PagesFacade */
    private $pagesFacade;

    /** @var RoutesFacade */
    private $routesFacade;

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
     * @param Request $request
     * @return mixed
     * @throws Throwable
     */
    public function dispatch(Request $request)
    {
        try {
            $route = $this->routesFacade->queryOne(
                GetRouteBySubdomainAndUrlQuery::buildFromRequest($request)
            );
        } catch (RouteNotFoundException $ex) {
            throw new NotFoundHttpException();
        }

        switch ($route->model_type) {
            case Page::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->dispatchPage($route->model);

            case Route::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->dispatchRoute($route->model);

            default:
                throw new LogicException(sprintf(
                    'Do not know how to dispatch route [model_type=%s].', $route->model_type
                ));
        }
    }

    /**
     * @param Page $page
     * @return ViewContract
     * @throws Exception
     */
    private function dispatchPage(Page $page): ViewContract
    {
        if ($this->gate->denies('show', [$page])) {
            throw new NotFoundHttpException();
        }

        return view('frontend.views.pages.show', [
            'renderedPage' => $this->pagesFacade->render($page),
        ]);
    }

    /**
     * @param Route $route
     * @return RedirectResponse
     */
    private function dispatchRoute(Route $route): RedirectResponse
    {
        return new RedirectResponse(
            $route->getEntireUrl()
        );
    }
}
