<?php

namespace App\Grid\Sources;

use App\Core\Models\Model;
use App\Grid\Query\GridQueryFilter;
use App\Grid\Query\GridQueryPagination;
use App\Grid\Query\GridQuerySorting;
use Illuminate\Support\Collection;

interface GridSource {

    /**
     * @param GridQueryFilter $filter
     * @return void
     */
    public function applyFilter(GridQueryFilter $filter): void;

    /**
     * @param GridQuerySorting $sorting
     * @return void
     */
    public function applySorting(GridQuerySorting $sorting): void;

    /**
     * @param GridQueryPagination $pagination
     * @return void
     */
    public function applyPagination(GridQueryPagination $pagination): void;

    /**
     * @return int
     */
    public function count(): int;

    /**
     * @return Collection|Model[]
     */
    public function get(): Collection;

}
