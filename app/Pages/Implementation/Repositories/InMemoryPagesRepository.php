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
        return $this->repository->getBy('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids): Collection {
        return $this->repository->getByMany('id', $ids);
    }

    /**
     * @inheritDoc
     */
    public function getByTagId(int $tagId): Collection {
        return $this->repository->getBy('tag_id', $tagId);
    }

    /**
     * @inheritDoc
     */
    public function persist(Page $page): void {
        $this->repository->persist($page);
    }

}
