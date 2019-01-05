<?php

namespace App\Tags\Implementation\Repositories;

use App\Core\Repositories\InMemoryRepository;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

class InMemoryTagsRepository implements TagsRepository {
    /** @var InMemoryRepository */
    private $repository;

    public function __construct(InMemoryRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Tag {
        return $this->repository->getBy('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function getByWebsiteIdAndName(int $websiteId, string $name): ?Tag {
        return $this->repository
            ->getAll()
            ->where('website_id', $websiteId)
            ->where('name', $name)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection {
        return $this->repository->getAll();
    }

    /**
     * @inheritDoc
     */
    public function persist(Tag $tag): void {
        $this->repository->persist($tag);
    }

    /**
     * @inheritDoc
     */
    public function delete(Tag $tag): void {
        $this->repository->delete($tag);
    }
}
