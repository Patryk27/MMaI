<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PageVariantsRepository;
use App\Pages\Models\PageVariant;
use App\Pages\Queries\GetPageVariantsByIdsQuery;
use App\Pages\Queries\GetPageVariantsByTagIdQuery;
use App\Pages\Queries\PageVariantsQuery;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Support\Collection;

class PageVariantsQuerier
{

    /**
     * @var PageVariantsRepository
     */
    private $pageVariantsRepository;

    /**
     * @var PageVariantsSearcher
     */
    private $pagesSearcher;

    /**
     * @param PageVariantsRepository $pageVariantsRepository
     * @param PageVariantsSearcher $pageVariantsSearcher
     */
    public function __construct(
        PageVariantsRepository $pageVariantsRepository,
        PageVariantsSearcher $pageVariantsSearcher
    ) {
        $this->pageVariantsRepository = $pageVariantsRepository;
        $this->pagesSearcher = $pageVariantsSearcher;
    }

    /**
     * @param PageVariantsQuery $query
     * @return Collection|PageVariant[]
     *
     * @throws PageException
     */
    public function query(PageVariantsQuery $query): Collection
    {
        switch (true) {
            case $query instanceof GetPageVariantsByIdsQuery:
                return $this->pageVariantsRepository->getByIds(
                    $query->getIds()
                );

            case $query instanceof GetPageVariantsByTagIdQuery:
                return $this->pageVariantsRepository->getByTagId(
                    $query->getTagId()
                );

            case $query instanceof SearchPageVariantsQuery:
                return $query->applyTo($this->pagesSearcher)->get();

            default:
                throw new PageException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * Returns number of page variants matching given query.
     *
     * @param PageVariantsQuery $query
     * @return int
     *
     * @throws PageException
     */
    public function count(PageVariantsQuery $query): int
    {
        switch (true) {
            case $query instanceof SearchPageVariantsQuery:
                return $query->applyTo($this->pagesSearcher)->count();

            default:
                return $this->query($query)->count();
        }
    }

}
