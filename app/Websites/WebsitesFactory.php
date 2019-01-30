<?php

namespace App\Websites;

use App\Websites\Implementation\Repositories\WebsitesRepository;
use App\Websites\Implementation\Services\WebsitesQuerier;

final class WebsitesFactory {

    public static function build(
        WebsitesRepository $websitesRepository
    ): WebsitesFacade {
        $websitesQuerier = new WebsitesQuerier($websitesRepository);

        return new WebsitesFacade(
            $websitesQuerier
        );
    }

}
