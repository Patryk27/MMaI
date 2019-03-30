<?php

namespace App\Grid\Implementation\Services\QueryBuilder;

use App\Grid\Query\GridQueryPagination;

final class PaginationParser {

    /**
     * @param array|null $request
     * @return GridQueryPagination
     */
    public function parse(?array $request): GridQueryPagination {
        if (is_null($request)) {
            $request = ['page' => 1, 'perPage' => 100];
        }

        // @todo validate

        return new GridQueryPagination($request);
    }

}
