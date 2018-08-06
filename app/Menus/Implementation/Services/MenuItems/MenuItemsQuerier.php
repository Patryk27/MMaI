<?php

namespace App\Menus\Implementation\Services\MenuItems;

use App\Menus\Implementation\Repositories\MenuItemsRepositoryInterface;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\GetMenuItemsByLanguageIdQuery;
use App\Menus\Queries\MenuItemsQueryInterface;
use Illuminate\Support\Collection;
use LogicException;

class MenuItemsQuerier
{

    /**
     * @var MenuItemsRepositoryInterface
     */
    private $menuItemsRepository;

    /**
     * @param MenuItemsRepositoryInterface $menuItemsRepository
     */
    public function __construct(
        MenuItemsRepositoryInterface $menuItemsRepository
    ) {
        $this->menuItemsRepository = $menuItemsRepository;
    }

    /**
     * @param MenuItemsQueryInterface $query
     * @return Collection|MenuItem[]
     */
    public function query(MenuItemsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetMenuItemsByLanguageIdQuery:
                return $this->menuItemsRepository->getByLanguageId(
                    $query->getLanguageId()
                );

            default:
                throw new LogicException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

}