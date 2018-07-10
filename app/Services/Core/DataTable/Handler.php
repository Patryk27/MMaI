<?php

namespace App\Services\Core\DataTable;

use App\Services\Core\Collection\Renderer;
use App\Services\Core\Searcher\SearcherInterface;
use Illuminate\Http\Request;

class Handler
{

    /**
     * @var SearcherInterface
     */
    private $searcher;

    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var Request
     */
    private $request;

    /**
     * Fetches items from given searcher (applying appropriate filters and so
     * on), renders them using given renderer and returns data which can be
     * directly passed into the DataTable plugin.
     *
     * @param SearcherInterface $searcher
     * @param Renderer $renderer
     * @param Request $request
     * @return array
     */
    public function handle(SearcherInterface $searcher, Renderer $renderer, Request $request): array
    {
        $this->searcher = $searcher;
        $this->renderer = $renderer;
        $this->request = $request;

        // Get number of all the rows;
        // We must do it here, because later we're adding filters, which will
        // limit this number.
        $numberOfAllRows = $this->searcher->getCount();

        // Apply filters
        $this->applySearchFilter();

        // Get number of rows after filtering;
        // It muse be done before we apply pagination, because that would affect
        // this number.
        $numberOfFilteredRows = $this->searcher->getCount();

        // Apply pagination and order
        $this->applyForPage();
        $this->applyOrderBy();

        // Fetch filtered, paginated and ordered rows
        $rows = $this->searcher->get();

        return [
            'recordsTotal' => $numberOfAllRows,
            'recordsFiltered' => $numberOfFilteredRows,
            'data' => $this->renderer->render($rows),
        ];
    }

    /**
     * Applies generic text search filter.
     *
     * @return void
     */
    private function applySearchFilter(): void
    {
        if ($this->request->has('search')) {
            $search = trim($this->request->get('search'));

            if (strlen($search) > 0) {
                $this->searcher->search($search);
            }
        }
    }

    /**
     * Applies pagination.
     *
     * @return void
     */
    private function applyForPage(): void
    {
        if ($this->request->has('pagination')) {
            $this->searcher->forPage(
                $this->request->input('pagination.page'),
                $this->request->input('pagination.perPage')
            );
        }
    }

    /**
     * Applies ordering.
     *
     * @return void
     */
    private function applyOrderBy(): void
    {
        if ($this->request->has('orderBy')) {
            $this->searcher->orderBy(
                $this->request->input('orderBy.column'),
                $this->request->input('orderBy.direction') === 'asc'
            );
        }
    }

}