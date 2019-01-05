<?php

namespace App\Pages\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Pages\Models\Page;
use App\Routes\RoutesFacade;
use Illuminate\Database\Connection as DatabaseConnection;
use Illuminate\Support\Collection;

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
     */
    public function persist(Page $page): void {
        unimplemented();
    }
}
