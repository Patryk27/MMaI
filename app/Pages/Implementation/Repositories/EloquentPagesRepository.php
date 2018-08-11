<?php

namespace App\Pages\Implementation\Repositories;

use App\Core\Exceptions\Exception as AppException;
use App\Core\Repositories\EloquentRepository;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Routes\Models\Route;
use App\Routes\RoutesFacade;
use Illuminate\Database\Connection as DatabaseConnection;
use Throwable;

class EloquentPagesRepository implements PagesRepositoryInterface
{

    /**
     * @var DatabaseConnection
     */
    private $db;

    /**
     * @var EloquentRepository
     */
    private $repository;

    /**
     * @var RoutesFacade
     */
    private $routesFacade;

    /**
     * @param DatabaseConnection $db
     * @param Page $page
     * @param RoutesFacade $routesFacade
     */
    public function __construct(
        DatabaseConnection $db,
        Page $page,
        RoutesFacade $routesFacade
    ) {
        $this->db = $db;
        $this->repository = new EloquentRepository($page);
        $this->routesFacade = $routesFacade;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Page
    {
        return $this->repository->getById($id);
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function persist(Page $page): void
    {
        $this->db->transaction(function () use ($page): void {
            $page->saveOrFail();

            foreach ($page->pageVariants as $pageVariant) {
                $this->persistPageVariant($page, $pageVariant);
            }
        });
    }

    /**
     * @param Page $page
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws Throwable
     */
    private function persistPageVariant(Page $page, PageVariant $pageVariant): void
    {
        $originalPageVariant = $pageVariant->fresh();

        $pageVariant->page()->associate($page);
        $pageVariant->saveOrFail();

        $this->persistRoute($pageVariant, isset($originalPageVariant) ? $originalPageVariant->route : null, $pageVariant->route);
    }

    /**
     * @param PageVariant $pageVariant
     * @param Route|null $oldRoute
     * @param Route|null $newRoute
     * @return void
     *
     * @throws AppException
     */
    private function persistRoute(PageVariant $pageVariant, ?Route $oldRoute, ?Route $newRoute): void
    {
        if (isset($oldRoute) && is_null($newRoute)) {
            $this->routesFacade->delete($oldRoute);
        }

        if (is_null($oldRoute) && isset($newRoute)) {
            $newRoute->setPointsAt($pageVariant);

            $this->routesFacade->persist($newRoute);
        }

        if (isset($oldRoute) && isset($newRoute)) {
            $newRoute->setPointsAt($pageVariant);

            if ($newRoute->url !== $oldRoute->url) {
                $this->routesFacade->persist($newRoute);
                $this->routesFacade->reroute($oldRoute, $newRoute);
            }
        }
    }

}