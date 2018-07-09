<?php

namespace App\Services\Core;

use Illuminate\Support\Collection;

interface SearcherInterface
{

    /**
     * Applies a generic text search filter.
     *
     * For instance when searching for "foo" in pages, it'll search through the
     * pages' titles, contents etc.
     *
     * @param string $search
     * @return void
     *
     * @see filter()
     */
    public function search(string $search): void;

    /**
     * Applies a precise field filter.
     *
     * For instance you may pass here e.g. ['type' => 'foo'].
     *
     * @param array $filters
     * @return void
     *
     * @see search()
     */
    public function filter(array $filters): void;

    /**
     * Changes order (sorting) of the result collection.
     *
     * @param string $field
     * @param bool $ascending
     * @return void
     */
    public function orderBy(string $field, bool $ascending): void;

    /**
     * Changes paging of the result collection.
     *
     * @param int $page
     * @param int $perPage
     * @return void
     */
    public function forPage(int $page, int $perPage): void;

    /**
     * Returns all the rows matching set criteria.
     *
     * @return Collection
     */
    public function get(): Collection;

    /**
     * Returns number of matching rows.
     * Does not fetch the rows themselves.
     *
     * @return int
     */
    public function getCount(): int;

}