<?php

namespace App\Core\Services\DataTables;

use Illuminate\Http\Request;

class Handler
{

    /**
     * @var callable
     * @see setQueryHandler()
     */
    private $queryHandler;

    /**
     * @var callable
     * @see setQueryCountHandler()
     */
    private $queryCountHandler;

    /**
     * Sets a function which will be called when DataTables Handler wants to
     * execute a query on given facade.
     *
     * A possible implementation can be:
     *   $handler->setQueryHandler(function (array $query): Collection {
     *     return $this->someFacade->queryMany(
     *       new SomeFacadeQuery($query)
     *     );
     *   });
     *
     * It is *mandatory* to set this handler before executing the handle()
     * method.
     *
     * @param callable $handler
     * @return void
     */
    public function setQueryHandler(callable $handler): void
    {
        $this->queryHandler = $handler;
    }

    /**
     * Sets a function which will be called when DataTables Handler wants to
     * execute a "get number of rows" query on given facade.
     *
     * A possible implementation can be:
     *   $handler->setQueryCountHandler(function (array $query): int {
     *     return $this->someFacade->queryCount(
     *       new SomeFacadeQuery($query)
     *     );
     *   });
     *
     * It is *mandatory* to set this handler before executing the handle()
     * method.
     *
     * @param callable $handler
     * @return void
     */
    public function setQueryCountHandler(callable $handler): void
    {
        $this->queryCountHandler = $handler;
    }

    /**
     * @param Request $request
     * @return array
     */
    public function handle(Request $request): array
    {
        $query = [];

        // Get number of all the rows;
        // We must do it here, because later we're going to add filters, which
        // can affect this number.
        $numberOfAllRows = ($this->queryCountHandler)($query);

        // Apply filters
        $query += $this->applySearchFilter($request);

        // Get number of rows after filtering;
        // We must do it here, because later we're going to add
        // pagination, which can affect this number.
        $numberOfFilteredRows = ($this->queryCountHandler)($query);

        // Apply pagination and order
        $query += $this->applyPagination($request);
        $query += $this->applyOrderBy($request);

        // Fetch filtered, paginated and ordered rows
        $rows = ($this->queryHandler)($query);

        return [
            'recordsTotal' => $numberOfAllRows,
            'recordsFiltered' => $numberOfFilteredRows,
            'data' => $rows,
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    private function applySearchFilter(Request $request): array
    {
        $search = $request->get('search', '');

        if (strlen($search) === 0) {
            return [];
        }

        return [
            'search' => $request->input('search'),
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    private function applyPagination(Request $request): array
    {
        if (!$request->has('pagination')) {
            return [];
        }

        return [
            'pagination' => [
                'page' => $request->input('pagination.page'),
                'perPage' => $request->input('pagination.perPage'),
            ],
        ];
    }

    /**
     * @param Request $request
     * @return array
     */
    private function applyOrderBy(Request $request): array
    {
        if (!$request->has('orderBy')) {
            return [];
        }

        return [
            'orderBy' => [
                $request->input('orderBy.column') => $request->input('orderBy.direction'),
            ],
        ];
    }

}