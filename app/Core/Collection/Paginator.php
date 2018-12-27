<?php

namespace App\Core\Collection;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class Paginator
{
    /** @var Request */
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Builds a LengthAwarePaginator for given collection.
     *
     * @param Collection $items
     * @param int $totalNumberOfItems
     * @param int $numberOfItemsPerPage
     * @return LengthAwarePaginator
     */
    public function build(Collection $items, int $totalNumberOfItems, int $numberOfItemsPerPage): LengthAwarePaginator
    {
        $paginator = new LengthAwarePaginator(
            $items,
            $totalNumberOfItems,
            $numberOfItemsPerPage,
            $this->getCurrentPageNumber()
        );

        $paginator->setPath(
            '/' . $this->request->path()
        );

        return $paginator;
    }

    /**
     * Returns current page number basing on the request (e.g. the "?page"
     * parameter).
     *
     * @return int
     */
    public function getCurrentPageNumber(): int
    {
        return $this->request->get('page') ?? 1;
    }
}
