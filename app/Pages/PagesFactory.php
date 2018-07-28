<?php

namespace App\Pages;

use App\Pages\Internal\Services\Pages\PagesCreator;
use App\Pages\Internal\Services\Pages\PagesUpdater;

class PagesFactory
{

    /**
     * @return PagesFacade
     */
    public static function build(): PagesFacade
    {
        $pagesCreator = new PagesCreator();
        $pagesUpdater = new PagesUpdater();

        return new PagesFacade(
            $pagesCreator,
            $pagesUpdater
        );
    }

}