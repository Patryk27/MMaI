<?php

namespace App\Pages\Implementation\Repositories;

use Illuminate\Support\Collection;

class InMemoryPageVariantsRepository implements PageVariantsRepositoryInterface
{

    /**
     * @inheritDoc
     */
    public function getByIds(array $ids): Collection
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function getByTagId(int $tagId): Collection
    {
        unimplemented();
    }

}
