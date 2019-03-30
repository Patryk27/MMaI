<?php

namespace App\Grid\Implementation\Services;

use App\Grid\Query\GridQuery;
use App\Grid\Response\GridResponse;
use App\Grid\Sources\GridSource;

final class GridQueryExecutor {

    /**
     * @param GridQuery $query
     * @param GridSource $source
     * @return GridResponse
     */
    public function executeQuery(GridQuery $query, GridSource $source): GridResponse {
        $totalCount = $source->count();

        foreach ($query->getFilters() as $filter) {
            $source->applyFilter($filter);
        }

        $matchingCount = $source->count();

        $source->applySorting($query->getSorting());
        $source->applyPagination($query->getPagination());

        $items = $source->get();

        return new GridResponse([
            'totalCount' => $totalCount,
            'matchingCount' => $matchingCount,
            'items' => $items,
        ]);
    }

}
