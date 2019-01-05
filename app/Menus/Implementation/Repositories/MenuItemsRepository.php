<?php

namespace App\Menus\Implementation\Repositories;

use App\Menus\Models\MenuItem;
use Illuminate\Support\Collection;

interface MenuItemsRepository {
    /**
     * Returns all the menu items that belong to specified website.
     *
     * @param int $websiteId
     * @return Collection|MenuItem[]
     */
    public function getByWebsiteId(int $websiteId): Collection;
}
