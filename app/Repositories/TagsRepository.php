<?php

namespace App\Repositories;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class TagsRepository
{

    /**
     * @var GenericRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(
            new Tag()
        );
    }

    /**
     * Returns all the tags.
     *
     * @return EloquentCollection|Tag[]
     */
    public function getAll(): EloquentCollection
    {
        return $this->repository->getAll();
    }

}