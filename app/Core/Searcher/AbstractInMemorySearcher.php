<?php

namespace App\Core\Searcher;

use Illuminate\Support\Collection;

/**
 * This class provides a base for an in-memory-based searcher.
 *
 * Since it would be pretty complex to model such searcher in an in-memory
 * fashion, we do not cover those in unit tests.
 *
 * We need a dummy implementation though to construct instances of the
 * @see \App\Pages\PagesFacade for instance.
 */
abstract class AbstractInMemorySearcher implements Searcher
{
    /**
     * @inheritDoc
     */
    public function search(string $query): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function filter(array $filters): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function orderBy(string $fieldName, bool $ascending): void
    {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function paginate(int $page, int $perPage): void
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
    public function count(): int
    {
        unimplemented();
    }
}
