<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\IntrinsicPages\IntrinsicPagesFacade;
use App\IntrinsicPages\Models\IntrinsicPage;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Routes\Exceptions\RouteNotFoundException;
use App\Routes\Models\Route;
use App\Routes\Queries\GetRouteByUrlQuery;
use App\Routes\RoutesFacade;
use Illuminate\Http\RedirectResponse;
use LogicException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class DispatchController extends Controller
{

    /**
     * @var IntrinsicPagesFacade
     */
    private $intrinsicPagesFacade;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param IntrinsicPagesFacade $intrinsicPagesFacade
     * @param PagesFacade $pagesFacade
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        IntrinsicPagesFacade $intrinsicPagesFacade,
        PagesFacade $pagesFacade,
        RoutesFacade $routesFacade
    ) {
        $this->intrinsicPagesFacade = $intrinsicPagesFacade;
        $this->pagesFacade = $pagesFacade;
        $this->routesFacade = $routesFacade;
    }

    /**
     * @param string|null $url
     * @return mixed
     *
     * @throws Throwable
     */
    public function show(?string $url = null)
    {
        try {
            $route = $this->routesFacade->queryOne(
                new GetRouteByUrlQuery(empty($url) ? '/' : $url)
            );
        } catch (RouteNotFoundException $ex) {
            throw new NotFoundHttpException();
        }

        switch ($route->model_type) {
            case IntrinsicPage::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->intrinsicPagesFacade->render($route->model);

            case PageVariant::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return view('frontend.pages.pages.show', [
                    'renderedPage' => $this->pagesFacade->render($route->model),
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