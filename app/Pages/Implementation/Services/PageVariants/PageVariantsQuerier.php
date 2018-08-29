<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PageVariantsRepositoryInterface;
use App\Pages\Models\PageVariant;
use App\Pages\Queries\GetPageVariantsByIdsQuery;
use App\Pages\Queries\PageVariantsQueryInterface;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Support\Collection;

class PageVariantsQuerier
{

    /**
     * @var PageVariantsRepositoryInterface
     */
    private $pageVariantsRepository;

    /**
     * @var PageVariantsSearcherInterface
     */
    private $pagesSearcher;

    /**
     * @param PageVariantsRepositoryInterface $pageVariantsRepository
     * @param PageVariantsSearcherInterface $pageVariantsSearcher
     */
    public function __construct(
        PageVariantsRepositoryInterface $pageVariantsRepository,
        PageVariantsSearcherInterface $pageVariantsSearcher
    ) {
        $this->pageVariantsRepository = $pageVariantsRepository;
        $this->pagesSearcher = $pageVariantsSearcher;
    }

    /**
     * @param PageVariantsQueryInterface $query
     * @return Collection|PageVariant[]
     *
     * @throws PageException
     */
    public function query(PageVariantsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof SearchPageVariantsQuery:
                return $query->applyTo($this->pagesSearcher)->get();

            case $query instanceof GetPageVariantsByIdsQuery:
                return $this->pageVariantsRepository->getByIds(
                    $query->getIds()
                );

            default:
                throw new PageException(
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
     * @throws PageException
     */
    public function queryCount(PageVariantsQueryInterface $query): int
    {
        switch (true) {
            case $query instanceof SearchPageVariantsQuery:
                return $query->applyTo($this->pagesSearcher)->getCount();

            default:
                return $this->query($query)->count();
        }
    }

}