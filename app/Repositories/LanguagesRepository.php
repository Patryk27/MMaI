<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use App\Models\Language;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class LanguagesRepository
{

    /**
     * @var GenericRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(
            new Language()
        );
    }

    /**
     * Returns language with given id or `null` if no such language exists.
     *
     * @param int $id
     * @return Language|null
     */
    public function getById(int $id): ?Language
    {
        return $this->repository->getById($id);
    }

    /**
     * Returns language with given id or throws an exception of no such
     * language exists.
     *
     * @param int $id
     * @return Language
     *
     * @throws RepositoryException
     */
    public function getByIdOrFail(int $id): Language
    {
        return $this->repository->getByIdOrFail($id);
    }

    /**
     * Returns all the languages.
     *
     * @return EloquentCollection|Language[]
     */
    public function getAll(): EloquentCollection
    {
        return $this->repository->getAll();
    }

}