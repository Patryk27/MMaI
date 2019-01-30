<?php

namespace App\Menus;

use App\Menus\Implementation\Repositories\MenuItemsRepository;
use App\Menus\Implementation\Services\MenuItemsQuerier;

final class MenusFactory {

    public static function build(
        MenuItemsRepository $menuItemsRepository
    ): MenusFacade {
        $menuItemsQuerier = new MenuItemsQuerier($menuItemsRepository);

        return new MenusFacade(
            $menuItemsQuerier
        );
    }

}
