<?php

namespace App\Grid\Query;

use App\Core\ValueObjects\HasInitializationConstructor;

final class GridQuery {

    use HasInitializationConstructor;

    /** @var GridQueryFilter[] */
    private $filters;

    /** @var GridQueryPagination */
    private $pagination;

    /** @var GridQuerySorting */
    private $sorting;

    /**
     * @return GridQueryFilter[]
     */
    public function getFilters(): array {
        return $this->filters;
    }

    /**
     * @return GridQueryPagination
     */
    public function getPagination(): GridQueryPagination {
        return $this->pagination;
    }

    /**
     * @return GridQuerySorting
     */
    public function getSorting(): GridQuerySorting {
        return $this->sorting;
    }

}
