<?php

namespace App\Tags\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;
use Throwable;

class EloquentTagsRepository implements TagsRepository
{
    /** @var EloquentRepository */
    private $repository;

    public function __construct(Tag $tag)
    {
        $this->repository = new EloquentRepository($tag);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Tag
    {
        return $this->repository->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function getByLanguageIdAndName(int $languageId, string $name): ?Tag
    {
        $stmt = $this->repository->newQuery();
        $stmt
            ->where('language_id', $languageId)
            ->where('name', $name);

        return $stmt->first();
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection
    {
        return $this->repository
            ->getAll()
            ->sortBy('name');
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function persist(Tag $tag): void
    {
        $this->repository->persist($tag);
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function delete(Tag $tag): void
    {
        $this->repository->delete($tag);
    }
}
