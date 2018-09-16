<?php

namespace App\Menus;

use App\Menus\Exceptions\MenuException;
use App\Menus\Exceptions\MenuItemNotFoundException;
use App\Menus\Implementation\Services\MenuItemsQuerier;
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
     * Returns the first menu item matching given query.
     * Throws an exception if no such menu item exists.
     *
     * @param MenuItemsQueryInterface $query
     * @return MenuItem
     *
     * @throws MenuException
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
     * Returns all menu items matching given query.
     *
     * @param MenuItemsQueryInterface $query
     * @return Collection|MenuItem[]
     *
     * @throws MenuException
     */
    public function queryMany(MenuItemsQueryInterface $query): Collection
    {
        return $this->menuItemsQuerier->query($query);
    }

}
