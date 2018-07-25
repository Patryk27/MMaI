<?php

namespace App\Pages\Internal\Repositories;

use App\Core\Exceptions\RepositoryException;
use App\Pages\Models\Page;
use Throwable;

interface PagesRepositoryInterface
{

    /**
     * Returns page with given id or `null` if no such page exists.
     *
     * @param int $id
     * @return Page|null
     */
    public function getById(int $id): ?Page;

    /**
     * Returns page with given id or throws an exception if no such page exists.
     *
     * @param int $id
     * @return Page
     *
     * @throws RepositoryException
     */
    public function getByIdOrFail(int $id): Page;

    /**
     * Saves given page in the database.
     *
     * @param Page $page
     * @return void
     *
     * @throws Throwable
     */
    public function persist(Page $page): void;

}