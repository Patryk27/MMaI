<?php

namespace App\Services\Routes;

use App\Models\InternalPage;
use App\Models\PageVariant;
use App\Models\Route;
use App\Services\InternalPages\Renderer as InternalPagesRenderer;
use App\Services\PageVariants\Renderer as PageVariantsRenderer;
use Exception;
use Illuminate\Contracts\View\Factory as ViewFactoryContract;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\RedirectResponse;
use LogicException;

class Dispatcher
{

    /**
     * @var ViewFactoryContract
     */
    private $viewFactory;

    /**
     * @var InternalPagesRenderer
     */
    private $internalPagesRenderer;

    /**
     * @var PageVariantsRenderer
     */
    private $pageVariantsRenderer;

    /**
     * @param ViewFactoryContract $viewFactory
     * @param InternalPagesRenderer $internalPagesRenderer
     * @param PageVariantsRenderer $pageVariantsRenderer
     */
    public function __construct(
        ViewFactoryContract $viewFactory,
        InternalPagesRenderer $internalPagesRenderer,
        PageVariantsRenderer $pageVariantsRenderer
    ) {
        $this->viewFactory = $viewFactory;
        $this->internalPagesRenderer = $internalPagesRenderer;
        $this->pageVariantsRenderer = $pageVariantsRenderer;
    }

    /**
     * @param Route $route
     * @return mixed
     *
     * @throws Exception
     */
    public function dispatch(Route $route)
    {
        switch ($route->model_type) {
            case InternalPage::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->dispatchInternalPage($route->model);

            case Route::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->dispatchRoute($route->model);

            case PageVariant::getMorphableType():
                /** @noinspection PhpParamsInspection */
                return $this->dispatchPageVariant($route->model);

            default:
                throw new LogicException(
                    sprintf('Don\'t know how to dispatch route with [model_type=%s].', $route->model_type)
                );
        }
    }

    /**
     * @param InternalPage $internalPage
     * @return mixed
     */
    private function dispatchInternalPage(InternalPage $internalPage)
    {
        return $this->internalPagesRenderer->render($internalPage);
    }

    /**
     * @param Route $route
     * @return RedirectResponse
     */
    private function dispatchRoute(Route $route): RedirectResponse
    {
        return new RedirectResponse('/' . $route->url);
    }

    /**
     * @param PageVariant $pageVariant
     * @return ViewContract
     *
     * @throws Exception
     */
    private function dispatchPageVariant(PageVariant $pageVariant): ViewContract
    {
        $view = $this->viewFactory->make('frontend.pages.pages.show');
        $view->with('renderedPageVariant', $this->pageVariantsRenderer->render($pageVariant));

        return $view;
    }

}