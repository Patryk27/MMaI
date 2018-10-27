<?php

namespace App\Pages\Implementation\Repositories;

use App\Pages\Models\PageVariant;
use Illuminate\Support\Collection;

interface PageVariantsRepository
{

    /**
     * Returns all page variants with given ids.
     *
     * @param int[] $ids
     * @return Collection|PageVariant[]
     */
    public function getByIds(array $ids): Collection;

    /**
     * Returns all page variants assigned to given tag.
     *
     * @param int $tagId
     * @return Collection|PageVariant[]
     */
    public function getByTagId(int $tagId): Collection;

}
