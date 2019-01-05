<?php

namespace App\Core\Api\Searcher;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

final class Querier {
    /** @var callable */
    private $counter;

    /** @var callable */
    private $fetcher;

    /**
     * @param callable $counter
     * @return void
     */
    public function setCounter(callable $counter): void {
        $this->counter = $counter;
    }

    /**
     * @param callable $fetcher
     * @return void
     */
    public function setFetcher(callable $fetcher): void {
        $this->fetcher = $fetcher;
    }

    /**
     * Returns number of all the facade's models that exist (e.g. number of all
     * the tags there are).
     *
     * @return int
     */
    public function countAllItems(): int {
        return ($this->counter)([]);
    }

    /**
     * Returns number of all the facade's models that match given filters (e.g.
     * number of all the English tags there are).
     *
     * @param array $query
     * @return int
     */
    public function countMatchingItems(array $query): int {
        if (isset($query['pagination'])) {
            unset($query['pagination']);
        }

        return ($this->counter)($query);
    }

    /**
     * Returns all the facade's models that match given filters and pagination.
     *
     * @param array $query
     * @return Collection|Model[]
     */
    public function searchItems(array $query): Collection {
        return ($this->fetcher)($query);
    }
}
