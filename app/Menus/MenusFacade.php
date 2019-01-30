<?php

namespace App\Menus;

use App\Menus\Exceptions\MenuException;
use App\Menus\Exceptions\MenuItemNotFoundException;
use App\Menus\Implementation\Services\MenuItemsQuerier;
use App\Menus\Models\MenuItem;
use App\Menus\Queries\MenuItemsQuery;
use Illuminate\Support\Collection;

final class MenusFacade {

    /** @var MenuItemsQuerier */
    private $menuItemsQuerier;

    public function __construct(MenuItemsQuerier $menuItemsQuerier) {
        $this->menuItemsQuerier = $menuItemsQuerier;
    }

    /**
     * Returns the first menu item matching given query.
     * Throws an exception if no such menu item exists.
     *
     * @param MenuItemsQuery $query
     * @return MenuItem
     * @throws MenuException
     * @throws MenuItemNotFoundException
     */
    public function queryOne(MenuItemsQuery $query): MenuItem {
        $menuItems = $this->queryMany($query);

        if ($menuItems->isEmpty()) {
            throw new MenuItemNotFoundException();
        }

        return $menuItems->first();
    }

    /**
     * Returns all menu items matching given query.
     *
     * @param MenuItemsQuery $query
     * @return Collection|MenuItem[]
     * @throws MenuException
     */
    public function queryMany(MenuItemsQuery $query): Collection {
        return $this->menuItemsQuerier->query($query);
    }

}
