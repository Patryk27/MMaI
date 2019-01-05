<?php

namespace App\Pages\Implementation\Repositories;

use App\Pages\Models\Page;
use Illuminate\Support\Collection;

interface PagesRepository {
    /**
     * Returns page with given id or `null` if no such page exists.
     *
     * @param int $id
     * @return Page|null
     */
    public function getById(int $id): ?Page;

    /**
     * Returns all the pages with given ids.
     *
     * @param int[] $ids
     * @return Collection|Page[]
     */
    public function getByIds(array $ids): Collection;

    /**
     * Returns all pages assigned to given tag.
     *
     * @param int $tagId
     * @return Collection|Page[]
     */
    public function getByTagId(int $tagId): Collection;

    /**
     * Saves given page in the database.
     *
     * @param Page $page
     * @return void
     */
    public function persist(Page $page): void;
}
