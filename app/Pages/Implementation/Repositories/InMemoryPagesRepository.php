<?php

namespace App\Pages\Implementation\Repositories;

use App\Core\Repositories\InMemoryRepository;
use App\Pages\Models\Page;
use Illuminate\Support\Collection;

class InMemoryPagesRepository implements PagesRepository {
    /** @var InMemoryRepository */
    private $repository;

    public function __construct(InMemoryRepository $repository) {
        $this->repository = $repository;
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
