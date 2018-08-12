<?php

namespace App\Menus\Implementation\Services\MenuItems;

use App\Menus\Exceptions\MenuException;
use App\Menus\Implementation\Repositories\MenuItemsRepositoryInterface;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\GetMenuItemsByLanguageIdQuery;
use App\Menus\Queries\MenuItemsQueryInterface;
use Illuminate\Support\Collection;

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
     *
     * @throws MenuException
     */
    public function query(MenuItemsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetMenuItemsByLanguageIdQuery:
                return $this->menuItemsRepository->getByLanguageId(
                    $query->getLanguageId()
                );

            default:
                throw new MenuException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

}