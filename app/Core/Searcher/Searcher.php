<?php

namespace App\Core\Searcher;

use Illuminate\Support\Collection;

// @todo describe + provide examples
interface Searcher {

    /**
     * Applies a text filter.
     *
     * @param string $query
     * @return void
     */
    public function search(string $query): void;

    /**
     * Applies a per-field search filter.
     *
     * @param array $filters
     * @return void
     */
    public function filter(array $filters): void;

    /**
     * Sorts the results.
     *
     * @param string $fieldName
     * @param bool $ascending
     * @return void
     */
    public function orderBy(string $fieldName, bool $ascending): void;

    /**
     * Paginates the results.
     *
     * @param int $page
     * @param int $perPage
     * @return void
     */
    public function paginate(int $page, int $perPage): void;

    /**
     * Returns all the rows matching set criteria.
     *
     * @return Collection
     */
    public function get(): Collection;

    /**
     * Returns number of matching rows without fetching rows from the database.
     *
     * @return int
     */
    public function count(): int;

}
