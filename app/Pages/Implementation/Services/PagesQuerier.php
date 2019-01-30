<?php

namespace App\Pages\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Pages\Queries\GetPagesByIds;
use App\Pages\Queries\GetPagesByTagId;
use App\Pages\Queries\PagesQuery;
use App\Pages\Queries\SearchPages;
use Illuminate\Support\Collection;

class PagesQuerier {

    /** @var PagesRepository */
    private $pagesRepository;

    /** @var PagesSearcher */
    private $pagesSearcher;

    public function __construct(
        PagesRepository $pagesRepository,
        PagesSearcher $pagesSearcher
    ) {
        $this->pagesRepository = $pagesRepository;
        $this->pagesSearcher = $pagesSearcher;
    }

    /**
     * @param PagesQuery $query
     * @return Collection|Page[]
     * @throws PageException
     */
    public function query(PagesQuery $query): Collection {
        switch (true) {
            case $query instanceof GetPagesByIds:
                return $this->pagesRepository->getByIds(
                    $query->getIds()
                );

            case $query instanceof GetPagesByTagId:
                return $this->pagesRepository->getByTagId(
                    $query->getTagId()
                );

            case $query instanceof SearchPages:
                return $query->applyTo($this->pagesSearcher)->get();

            default:
                throw new PageException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

    /**
     * Returns number of pages matching given query.
     *
     * @param PagesQuery $query
     * @return int
     * @throws PageException
     */
    public function count(PagesQuery $query): int {
        switch (true) {
            case $query instanceof SearchPages:
                return $query->applyTo($this->pagesSearcher)->count();

            default:
                return $this->query($query)->count();
        }
    }

}
