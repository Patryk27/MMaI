<?php

namespace App\Grid\Implementation\Services;

use App\Grid\Implementation\Services\QueryBuilder\FiltersParser;
use App\Grid\Implementation\Services\QueryBuilder\PaginationParser;
use App\Grid\Implementation\Services\QueryBuilder\SortingParser;
use App\Grid\Query\GridQuery;
use App\Grid\Requests\GridRequest;

final class GridQueryBuilder {

    /** @var FiltersParser */
    private $filtersParser;

    /** @var PaginationParser */
    private $paginationParser;

    /** @var SortingParser */
    private $sortingParser;

    public function __construct(
        FiltersParser $filtersParser,
        PaginationParser $paginationParser,
        SortingParser $sortingParser
    ) {
        $this->filtersParser = $filtersParser;
        $this->paginationParser = $paginationParser;
        $this->sortingParser = $sortingParser;
    }

    /**
     * @param GridRequest $request
     * @return GridQuery
     */
    public function fromRequest(GridRequest $request): GridQuery {
        return new GridQuery([
            'filters' => $this->filtersParser->parse($request->get('filters')),
            'pagination' => $this->paginationParser->parse($request->get('pagination')),
            'sorting' => $this->sortingParser->parse($request->get('sorting')),
        ]);
    }

}
