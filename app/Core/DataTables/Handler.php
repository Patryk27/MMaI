<?php

namespace App\Core\DataTables;

use Illuminate\Http\Request;

class Handler
{

    /**
     * @var callable
     * @see setRowsFetcher()
     */
    private $rowsFetcher;

    /**
     * @var callable
     * @see setRowsCounter()
     */
    private $rowsCounter;

    /**
     * Sets a function used to fetch rows from the source.
     *
     * Example implementation:
     *   $handler->setRowsFetcher(function (array $query): Collection {
     *     return $this->someFacade->queryMany(
     *       new SomeFacadeQuery($query)
     *     );
     *   });
     *
     * It is *mandatory* to set this handler before executing the handle()
     * method.
     *
     * @param callable $rowsFetcher
     * @return void
     */
    public function setRowsFetcher(callable $rowsFetcher): void
    {
        $this->rowsFetcher = $rowsFetcher;
    }

    /**
     * Sets a function used to count rows from the source.
     *
     * Example implementation:
     *   $handler->setRowsCounter(function (array $query): int {
     *     return $this->someFacade->queryCount(
     *       new SomeFacadeQuery($query)
     *     );
     *   });
     *
     * It is *mandatory* to set this handler before executing the handle()
     * method.
     *
     * @param callable $rowsCounter
     * @return void
     */
    public function setRowsCounter(callable $rowsCounter): void
    {
        $this->rowsCounter = $rowsCounter;
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
        $numberOfAllRows = ($this->rowsCounter)($query);

        // Apply filters
        $query += $this->applySearchFilter($request);
        $query += $this->applyFieldsFilters($request);

        // Get number of rows after filtering;
        // We must do it here, because later we're going to add
        // pagination, which can affect this number.
        $numberOfFilteredRows = ($this->rowsCounter)($query);

        // Apply pagination and order
        $query += $this->applyPagination($request);
        $query += $this->applyOrderBy($request);

        // Fetch filtered, paginated and ordered rows
        $rows = ($this->rowsFetcher)($query);

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
    private function applyFieldsFilters(Request $request): array
    {
        return [
            'filters' => $request->get('filters', [])
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
