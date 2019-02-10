<?php

namespace App\Pages\Implementation\Repositories;

use App\Core\Exceptions\Exception;
use App\Core\Repositories\EloquentRepository;
use App\Pages\Models\Page;
use App\Routes\Exceptions\RouteException;
use App\Routes\Models\Route;
use App\Routes\Requests\CreateRoute;
use App\Routes\RoutesFacade;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;
use Throwable;

class EloquentPagesRepository implements PagesRepository {

    /** @var DatabaseConnection */
    private $db;

    /** @var EloquentRepository */
    private $repository;

    /** @var RoutesFacade */
    private $routesFacade;

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
    public function getById(int $id): ?Page {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids): Collection {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function getByTagId(int $tagId): Collection {
        unimplemented();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function persist(Page $page): void {
        $this->db->transaction(function () use ($page) {
            $oldPage = $page->fresh();

            $this->repository->persist($page);

            $this->persistRoute($page, isset($oldPage) ? $oldPage->route : null, $page->route);
            $this->persistAttachments($page);
            $this->persistTags($page);
        });
    }

    /**
     * @param Page $page
     * @param Route|null $previousRoute
     * @param Route|null $newRoute
     * @return void
     * @throws RouteException
     */
    private function persistRoute(Page $page, ?Route $previousRoute, ?Route $newRoute): void {
        if (isset($previousRoute)) {
            if (isset($newRoute)) {
                if ($newRoute->getAbsoluteUrl() === $previousRoute->getAbsoluteUrl()) {
                    return;
                }

                $newRoute = $this->routesFacade->create(new CreateRoute([
                    'subdomain' => $newRoute->subdomain,
                    'url' => $newRoute->url,
                    'model_type' => Page::getMorphableType(),
                    'model_id' => $page->id,
                ]));

                $this->routesFacade->redirect($previousRoute, $newRoute);
            } else {
                $this->routesFacade->delete($previousRoute);
            }
        } else {
            if (isset($newRoute)) {
                $this->routesFacade->create(new CreateRoute([
                    'subdomain' => $newRoute->subdomain,
                    'url' => $newRoute->url,
                    'model_type' => Page::getMorphableType(),
                    'model_id' => $page->id,
                ]));
            }
        }
    }

    /**
     * @param Page $page
     * @return void
     * @throws Exception
     */
    private function persistAttachments(Page $page): void {
        foreach ($page->attachments as $attachment) {
            $attachment->page_id = $page->id;
            $attachment->saveOrThrow();
        }
    }

    /**
     * @param Page $page
     * @return void
     */
    private function persistTags(Page $page): void {
        $page->tags()->sync(
            $page->tags->pluck('id')
        );
    }

}
