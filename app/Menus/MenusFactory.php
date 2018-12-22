<?php

namespace App\Menus;

use App\Menus\Implementation\Repositories\MenuItemsRepository;
use App\Menus\Implementation\Services\MenuItemsQuerier;

final class MenusFactory
{
    /**
     * Builds an instance of @see MenusFacade.
     *
     * @param MenuItemsRepository $menuItemsRepository
     * @return MenusFacade
     */
    public static function build(
        MenuItemsRepository $menuItemsRepository
    ): MenusFacade {
        $menuItemsQuerier = new MenuItemsQuerier($menuItemsRepository);

        return new MenusFacade(
            $menuItemsQuerier
        );
    }
}
