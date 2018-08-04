<?php

namespace App\Pages\Implementation\Services\PageVariants\Searcher;

use App\Pages\Implementation\Services\PageVariants\PageVariantsSearcherInterface;
use Illuminate\Support\Collection;

class InMemoryPageVariantsSearcher implements PageVariantsSearcherInterface
{

    /**
     * @inheritDoc
     */
    public function search(string $search): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $fields): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $field, bool $ascending): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function forPage(int $page, int $perPage): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function get(): Collection
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function getCount(): int
    {
        unimplemented();
    }

}