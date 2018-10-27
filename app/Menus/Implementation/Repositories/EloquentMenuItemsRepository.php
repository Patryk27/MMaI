<?php

namespace App\Menus\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Menus\Models\MenuItem;
use Illuminate\Support\Collection;

class EloquentMenuItemsRepository implements MenuItemsRepository
{

    /**
     * @var EloquentRepository
     */
    private $repository;

    /**
     * @param MenuItem $menuItem
     */
    public function __construct(
        MenuItem $menuItem
    ) {
        $this->repository = new EloquentRepository($menuItem);
    }

    /**
     * @inheritDoc
     */
    public function getByLanguageId(int $languageId): Collection
    {
        return $this->repository->getByMany('language_id', $languageId);
    }

}
