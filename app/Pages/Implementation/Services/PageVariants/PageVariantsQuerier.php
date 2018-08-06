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
     * Returns all page variants matching given query.
     *
     * @param PageVariantsQueryInterface $query
     * @return Collection|PageVariant[]
     *
     * @throws LogicException
     */
    public function query(PageVariantsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof SearchPageVariantsQuery:
                $query->applyTo($this->pagesSearcher);

                return $this->pagesSearcher->get();

            default:
                throw new LogicException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * Returns number of page variants matching given query.
     *
     * @param PageVariantsQueryInterface $query
     * @return int
     *
     * @throws LogicException
     */
    public function queryCount(PageVariantsQueryInterface $query): int
    {
        switch (true) {
            case $query instanceof SearchPageVariantsQuery:
                $query->applyTo($this->pagesSearcher);

                return $this->pagesSearcher->getCount();

            default:
                return $this->query($query)->count();
        }
    }

}