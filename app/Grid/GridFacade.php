<?php

namespace App\Grid;

use App\Grid\Implementation\Services\GridQueryBuilder;
use App\Grid\Implementation\Services\GridQueryExecutor;
use App\Grid\Query\GridQuery;
use App\Grid\Requests\GridRequest;
use App\Grid\Response\GridResponse;
use App\Grid\Sources\GridSource;

final class GridFacade {

    /** @var GridQueryBuilder */
    private $gridQueryBuilder;

    /** @var GridQueryExecutor */
    private $gridQueryExecutor;

    public function __construct(
        GridQueryBuilder $gridQueryBuilder,
        GridQueryExecutor $gridQueryExecutor
    ) {
        $this->gridQueryBuilder = $gridQueryBuilder;
        $this->gridQueryExecutor = $gridQueryExecutor;
    }

    /**
     * @todo description
     *
     * @param GridRequest $request
     * @return GridQuery
     */
    public function prepareQuery(GridRequest $request): GridQuery {
        return $this->gridQueryBuilder->fromRequest($request);
    }

    /**
     * @todo description
     *
     * @param GridQuery $query
     * @param GridSource $source
     * @return GridResponse
     */
    public function executeQuery(GridQuery $query, GridSource $source): GridResponse {
        return $this->gridQueryExecutor->executeQuery($query, $source);
    }

}
