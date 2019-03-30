<?php

namespace App\Grid\Sources;

use App\Grid\Query\GridQueryFilter;
use App\Grid\Query\GridQueryPagination;
use App\Grid\Query\GridQuerySorting;
use Illuminate\Support\Collection;

abstract class NullGridSource implements GridSource {

    /**
     * {@inheritdoc}
     */
    public function applyFilter(GridQueryFilter $filter): void {
        // Nottin' here
    }

    /**
     * {@inheritdoc}
     */
    public function applySorting(GridQuerySorting $sorting): void {
        // Nottin' here
    }

    /**
     * {@inheritdoc}
     */
    public function applyPagination(GridQueryPagination $pagination): void {
        // Nottin' here
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    public function get(): Collection {
        return new Collection();
    }

}
