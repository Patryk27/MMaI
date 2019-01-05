<?php

namespace App\Websites;

use App\Websites\Implementation\Repositories\WebsitesRepository;
use App\Websites\Implementation\Services\WebsitesQuerier;

final class WebsitesFactory {
    /**
     * Builds an instance of @see WebsitesFacade.
     *
     * @param WebsitesRepository $websitesRepository
     * @return WebsitesFacade
     */
    public static function build(
        WebsitesRepository $websitesRepository
    ): WebsitesFacade {
        $websitesQuerier = new WebsitesQuerier($websitesRepository);

        return new WebsitesFacade(
            $websitesQuerier
        );
    }
}
