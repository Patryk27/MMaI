<?php

namespace App\Menus\Implementation\Services;

use App\Menus\Exceptions\MenuException;
use App\Menus\Implementation\Repositories\MenuItemsRepository;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\GetMenuItemsByLanguageIdQuery;
use App\Menus\Queries\MenuItemsQuery;
use Illuminate\Support\Collection;

class MenuItemsQuerier
{
    /** @var MenuItemsRepository */
    private $menuItemsRepository;

    public function __construct(MenuItemsRepository $menuItemsRepository)
    {
        $this->menuItemsRepository = $menuItemsRepository;
    }

    /**
     * @param MenuItemsQuery $query
     * @return Collection|MenuItem[]
     * @throws MenuException
     */
    public function query(MenuItemsQuery $query): Collection
    {
        switch (true) {
            case $query instanceof GetMenuItemsByLanguageIdQuery:
                return $this->menuItemsRepository->getByLanguageId(
                    $query->getLanguageId()
                );

            default:
                throw new MenuException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }
}
