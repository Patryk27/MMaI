<?php

namespace App\Pages\Implementation\Repositories;

use App\Pages\Models\PageVariant;
use Illuminate\Support\Collection;

interface PageVariantsRepositoryInterface
{

    /**
     * Returns all page variants with given ids.
     *
     * @param int[] $ids
     * @return Collection|PageVariant[]
     */
    public function getByIds(array $ids): Collection;

}