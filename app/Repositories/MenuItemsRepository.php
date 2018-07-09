<?php

namespace App\Repositories;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class MenuItemsRepository
{

    /**
     * @var GenericRepository
     */
    private $repository;

    public function __construct()
    {
        $this->repository = new GenericRepository(
            new MenuItem()
        );
    }

    /**
     * Returns all menu items for given language.
     *
     * @param int $languageId
     * @return EloquentCollection|MenuItem[]
     */
    public function getByLanguageId(int $languageId): EloquentCollection
    {
        return $this->repository->getByMany('language_id', $languageId);
    }

}