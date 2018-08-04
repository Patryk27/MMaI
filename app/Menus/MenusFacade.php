<?php

namespace App\Menus;

use App\Menus\Exceptions\MenuItemNotFoundException;
use App\Menus\Implementation\Services\MenuItems\MenuItemsQuerier;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\MenuItemsQueryInterface;
use Illuminate\Support\Collection;

final class MenusFacade
{

    /**
     * @var MenuItemsQuerier
     */
    private $menuItemsQuerier;

    /**
     * @param MenuItemsQuerier $menuItemsQuerier
     */
    public function __construct(
        MenuItemsQuerier $menuItemsQuerier
    ) {
        $this->menuItemsQuerier = $menuItemsQuerier;
    }

    /**
     * @param MenuItemsQueryInterface $query
     * @return MenuItem
     *
     * @throws MenuItemNotFoundException
     */
    public function queryOne(MenuItemsQueryInterface $query): MenuItem
    {
        $menuItems = $this->queryMany($query);

        if ($menuItems->isEmpty()) {
            throw new MenuItemNotFoundException();
        }

        return $menuItems->first();
    }

    /**
     * @param MenuItemsQueryInterface $query
     * @return Collection|MenuItem[]
     */
    public function queryMany(MenuItemsQueryInterface $query): Collection
    {
        return $this->menuItemsQuerier->query($query);
    }

}