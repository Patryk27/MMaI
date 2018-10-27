<?php

namespace App\Pages\Implementation\Repositories;

use App\Pages\Models\Page;

interface PagesRepository
{

    /**
     * Returns page with given id or `null` if no such page exists.
     *
     * @param int $id
     * @return Page|null
     */
    public function getById(int $id): ?Page;

    /**
     * Saves given page in the database.
     *
     * @param Page $page
     * @return void
     */
    public function persist(Page $page): void;

}
