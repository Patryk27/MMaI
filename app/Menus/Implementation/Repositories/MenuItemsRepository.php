<?php

namespace App\Menus\Implementation\Repositories;

use App\Menus\Models\MenuItem;
use Illuminate\Support\Collection;

interface MenuItemsRepository
{
    /**
     * Returns all menu items which belong to given language.
     *
     * @param int $languageId
     * @return Collection|MenuItem[]
     */
    public function getByLanguageId(int $languageId): Collection;
}
