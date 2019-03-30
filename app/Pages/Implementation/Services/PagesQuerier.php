<?php

namespace App\Pages\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Pages\Queries\GetPagesByIds;
use App\Pages\Queries\GetPagesByTagId;
use App\Pages\Queries\PagesQuery;
use Illuminate\Support\Collection;

class PagesQuerier {

    /** @var PagesRepository */
    private $pagesRepository;

    public function __construct(PagesRepository $pagesRepository) {
        $this->pagesRepository = $pagesRepository;
    }

    /**
     * @param PagesQuery $query
     * @return Collection|Page[]
     * @throws PageException
     */
    public function query(PagesQuery $query): Collection {
        switch (true) {
            case $query instanceof GetPagesByIds:
                return $this->pagesRepository->getByIds($query->getIds());

            case $query instanceof GetPagesByTagId:
                return $this->pagesRepository->getByTagId($query->getTagId());

            default:
                throw new PageException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

}
