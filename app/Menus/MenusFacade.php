<?php

namespace App\Menus;

use App\Menus\Models\MenuItem;
use Illuminate\Support\Collection;

class MenusFacade
{

    /**
     * @param Queries\MenuQueryInterface $query
     * @return MenuItem
     */
    public function queryOne(Queries\MenuQueryInterface $query): MenuItem
    {
        unimplemented();
    }

    /**
     * @param Queries\MenuQueryInterface $query
     * @return Collection|MenuItem[]
     */
    public function queryMany(Queries\MenuQueryInterface $query): Collection
    {
        unimplemented();
    }

}