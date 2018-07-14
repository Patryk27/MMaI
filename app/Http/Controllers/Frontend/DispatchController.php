<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\RoutesRepository;
use App\Services\Routes\Dispatcher as RoutesDispatcher;
use Exception;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * This is the main frontend's dispatcher.
 *
 * It contains only one method and that one method is responsible for
 * translating URLs into routes and then dispatching them - most likely to the
 * @see PagesController.
 */
class DispatchController extends Controller
{

    /**
     * @var RoutesRepository
     */
    private $routesRepository;

    /**
     * @var RoutesDispatcher
     */
    private $routesDispatcher;

    /**
     * @param RoutesRepository $routesRepository
     * @param RoutesDispatcher $routesDispatcher
     */
    public function __construct(
        RoutesRepository $routesRepository,
        RoutesDispatcher $routesDispatcher
    ) {
        $this->routesRepository = $routesRepository;
        $this->routesDispatcher = $routesDispatcher;
    }

    /**
     * @param string|null $url
     * @return mixed
     *
     * @throws Exception
     */
    public function show(?string $url = null)
    {
        // In case of empty URL, fall back to "/";
        // The "/" route should be present in the database, whereas the null one
        // not necessarily.
        if (empty($url)) {
            $url = '/';
        }

        $route = $this->routesRepository->getByUrl($url);

        if (is_null($route)) {
            throw new NotFoundHttpException();
        }

        return $this->routesDispatcher->dispatch($route);
    }

}