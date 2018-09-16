<?php

namespace App\Pages\Implementation\Repositories;

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

            $this->persistAttachments($page);
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
        $this->persistTags($pageVariant);
    }

    /**
     * @param PageVariant $pageVariant
     * @param Route|null $oldRoute
     * @param Route|null $newRoute
     * @return void
     *
     * @throws Throwable
     */
    private function persistRoute(PageVariant $pageVariant, ?Route $oldRoute, ?Route $newRoute): void
    {
        if (isset($oldRoute) && is_null($newRoute)) {
            $this->routesFacade->delete($oldRoute);

            return;
        }

        if (is_null($oldRoute) && isset($newRoute)) {
            $newRoute->setPointsAt($pageVariant);
            $newRoute->saveOrFail();

            return;
        }

        if (isset($oldRoute) && isset($newRoute)) {
            $newRoute->setPointsAt($pageVariant);

            if ($newRoute->url !== $oldRoute->url) {
                $newRoute->saveOrFail();

                $this->routesFacade->reroute($oldRoute, $newRoute);
            }

            return;
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     */
    private function persistTags(PageVariant $pageVariant): void
    {
        $pageVariant->tags()->sync(
            $pageVariant->tags->pluck('id')
        );
    }

    /**
     * @param Page $page
     * @return void
     *
     * @throws Throwable
     */
    private function persistAttachments(Page $page): void
    {
        $originalPage = $page->fresh();

        // Detach all the attachments which were also detached by the user
        foreach ($originalPage->attachments as $attachment) {
            if (!$page->attachments->contains('id', $attachment->id)) {
                $attachment->fill([
                    'attachable_type' => null,
                    'attachable_id' => null,
                ]);

                $attachment->saveOrFail();
            }
        }

        // Save all the given attachments
        foreach ($page->attachments as $attachment) {
            $attachment->fill([
                'attachable_type' => Page::getMorphableType(),
                'attachable_id' => $page->id,
            ]);

            $attachment->saveOrFail();
        }
    }

}
