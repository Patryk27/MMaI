<?php

namespace App\Websites\Implementation\Services;

use App\Websites\Exceptions\WebsiteException;
use App\Websites\Implementation\Repositories\WebsitesRepository;
use App\Websites\Queries\GetAllWebsites;
use App\Websites\Queries\GetWebsiteBySlug;
use App\Websites\Queries\WebsitesQuery;
use Illuminate\Support\Collection;

class WebsitesQuerier {

    /** @var WebsitesRepository */
    private $websitesRepository;

    public function __construct(WebsitesRepository $websitesRepository) {
        $this->websitesRepository = $websitesRepository;
    }

    /**
     * @param WebsitesQuery $query
     * @return Collection
     * @throws WebsiteException
     */
    public function query(WebsitesQuery $query): Collection {
        switch (true) {
            case $query instanceof GetAllWebsites:
                return $this->websitesRepository->getAll();

            case $query instanceof GetWebsiteBySlug:
                return collect_one(
                    $this->websitesRepository->getBySlug(
                        $query->getSlug()
                    )
                );

            default:
                throw new WebsiteException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

}
