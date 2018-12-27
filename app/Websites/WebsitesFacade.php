<?php

namespace App\Websites;

use App\Websites\Exceptions\WebsiteException;
use App\Websites\Exceptions\WebsiteNotFoundException;
use App\Websites\Implementation\Services\WebsitesQuerier;
use App\Websites\Models\Website;
use App\Websites\Queries\WebsitesQuery;
use Illuminate\Support\Collection;

final class WebsitesFacade
{
    /** @var WebsitesQuerier */
    private $websitesQuerier;

    public function __construct(WebsitesQuerier $websitesQuerier)
    {
        $this->websitesQuerier = $websitesQuerier;
    }

    /**
     * @param WebsitesQuery $query
     * @return Website
     * @throws WebsiteException
     * @throws WebsiteNotFoundException
     */
    public function queryOne(WebsitesQuery $query): Website
    {
        $websites = $this->queryMany($query);

        if ($websites->isEmpty()) {
            throw new WebsiteNotFoundException();
        }

        return $websites->first();
    }

    /**
     * @param WebsitesQuery $query
     * @return Collection
     * @throws WebsiteException
     */
    public function queryMany(WebsitesQuery $query): Collection
    {
        return $this->websitesQuerier->query($query);
    }
}
