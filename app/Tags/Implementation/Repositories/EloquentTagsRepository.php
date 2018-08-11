<?php

namespace App\Tags\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

class EloquentTagsRepository implements TagsRepositoryInterface
{

    /**
     * @var EloquentRepository
     */
    private $repository;

    /**
     * @param Tag $tag
     */
    public function __construct(
        Tag $tag
    ) {
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
    public function getAll(): Collection
    {
        return $this->repository->getAll()->sortBy('name');
    }

}