<?php

namespace App\Tags\Implementation\Repositories;

use App\Core\Repositories\InMemoryRepository;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

class InMemoryTagsRepository implements TagsRepositoryInterface
{

    /**
     * @var InMemoryRepository
     */
    private $repository;

    /**
     * @param InMemoryRepository $repository
     */
    public function __construct(
        InMemoryRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Tag
    {
        return $this->repository->getBy('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

}