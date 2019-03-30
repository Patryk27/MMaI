<?php

namespace App\Grid\Implementation\Services\QueryBuilder;

use App\Grid\Query\GridQuerySorting;

final class SortingParser {

    /**
     * @param array|null $request
     * @return GridQuerySorting
     */
    public function parse(?array $request): GridQuerySorting {
        if (is_null($request)) {
            $request = ['field' => 'id', 'direction' => 'desc'];
        }

        // @todo validate

        return new GridQuerySorting($request);
    }

}
