<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Models\PageVariant;
use App\Pages\Queries\PageVariantsQueryInterface;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Support\Collection;
use LogicException;

class PageVariantsQuerier
{

    /**
     * @var PageVariantsSearcherInterface
     */
    private $pagesSearcher;

    /**
     * @param PageVariantsSearcherInterface $pageVariantsSearcher
     */
    public function __construct(
        PageVariantsSearcherInterface $pageVariantsSearcher
    ) {
        $this->pagesSearcher = $pageVariantsSearcher;
    }

    /**
     * @param PageVariantsQueryInterface $query
     * @return Collection|PageVariant[]
     *
     * @throws LogicException
     */
    public function query(PageVariantsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof SearchPageVariantsQuery:
                return $this->search($query);

            default:
                throw new LogicException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * @param PageVariantsQueryInterface $query
     * @return int
     *
     * @throws LogicException
     */
    public function queryCount(PageVariantsQueryInterface $query): int
    {
        return $this->query($query)->count(); // @todo provide a better implementation, especially for SearchPageVariantsQuery
    }

    /**
     * @param SearchPageVariantsQuery $query
     * @return Collection
     */
    private function search(SearchPageVariantsQuery $query): Collection
    {
        $this->pagesSearcher->filter($query->getFilter());

        foreach ($query->getOrderBy() as $field => $direction) {
            $this->pagesSearcher->orderBy($field, $direction === 'asc');
        }

        if ($query->hasPagination()) {
            $this->pagesSearcher->forPage(
                $query->getPagination()['page'],
                $query->getPagination()['perPage']
            );
        }

        return $this->pagesSearcher->get();
    }

}