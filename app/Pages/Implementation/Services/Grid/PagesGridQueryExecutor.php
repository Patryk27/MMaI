<?php

namespace App\Pages\Implementation\Services\Grid;

use App\Grid\GridFacade;
use App\Grid\Query\GridQuery;
use App\Grid\Response\GridResponse;
use App\Pages\Implementation\Services\Grid\Sources\PagesGridSource;

class PagesGridQueryExecutor {

    /** @var GridFacade */
    private $gridFacade;

    /** @var PagesGridSource */
    private $pagesGridSource;

    public function __construct(
        GridFacade $gridFacade,
        PagesGridSource $pagesGridSource
    ) {
        $this->gridFacade = $gridFacade;
        $this->pagesGridSource = $pagesGridSource;
    }

    /**
     * @param GridQuery $query
     * @return GridResponse
     */
    public function executeQuery(GridQuery $query): GridResponse {
        return $this->gridFacade->executeQuery($query, $this->pagesGridSource);
    }

}
