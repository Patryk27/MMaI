<?php

namespace App\Grid\Implementation\Services\QueryBuilder;

use App\Grid\Query\GridQueryFilter;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class FiltersParser {

    /**
     * @param array|null $request
     * @return GridQueryFilter[]
     */
    public function parse(?array $request): array {
        if (is_null($request)) {
            return [];
        }

        return array_map(function ($filter) {
            if (!is_array($filter)) {
                throw new BadRequestHttpException('Invalid filters specified (1).'); // @todo provide more context
            }

            return $this->parseOne($filter);
        }, $request);
    }

    /**
     * @param array $filter
     * @return GridQueryFilter
     */
    private function parseOne(array $filter): GridQueryFilter {
        foreach (['field', 'operator', 'value'] as $key) {
            if (!array_key_exists($key, $filter)) {
                throw new BadRequestHttpException('Invalid filters specified (2).'); // @todo provide more context
            }
        }

        return new GridQueryFilter([
            'field' => $filter['field'],
            'operator' => $filter['operator'],
            'value' => $filter['value'],
        ]);
    }

}
