<?php

namespace App\Services\Core\Searcher;

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
     * Applies a precise search filter.
     *
     * You can pass here an array describing precise filtering conditions, e.g.: [
     *  'title' => 'something',
     * ]
     *
     * @param array $fields
     * @return void
     *
     * @see search()
     */
    public function filter(array $fields): void;

    /**
     * Changes sorting of the result collection.
     *
     * @param string $field
     * @param bool $ascending
     * @return void
     */
    public function orderBy(string $field, bool $ascending): void;

    /**
     * Limits results to given page.
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