<?php

namespace App\Menus;

use App\Menus\Implementation\Repositories\MenuItemsRepositoryInterface;
use App\Menus\Implementation\Services\MenuItemsQuerier;

final class MenusFactory
{

    /**
     * Builds an instance of @see MenusFacade.
     *
     * @param MenuItemsRepositoryInterface $menuItemsRepository
     * @return MenusFacade
     */
    public static function build(
        MenuItemsRepositoryInterface $menuItemsRepository
    ): MenusFacade {
        $menuItemsQuerier = new MenuItemsQuerier($menuItemsRepository);

        return new MenusFacade(
            $menuItemsQuerier
        );
    }

}
