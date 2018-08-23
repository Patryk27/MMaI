<?php

namespace App\Pages;

use App\Pages\Implementation\Repositories\PagesRepositoryInterface;
use App\Pages\Implementation\Services\Pages\PagesCreator;
use App\Pages\Implementation\Services\Pages\PagesUpdater;
use App\Pages\Implementation\Services\PageVariants\PageVariantsCreator;
use App\Pages\Implementation\Services\PageVariants\PageVariantsQuerier;
use App\Pages\Implementation\Services\PageVariants\PageVariantsRenderer;
use App\Pages\Implementation\Services\PageVariants\PageVariantsSearcherInterface;
use App\Pages\Implementation\Services\PageVariants\PageVariantsUpdater;
use App\Pages\Implementation\Services\PageVariants\PageVariantsValidator;
use App\Tags\TagsFacade;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

final class PagesFactory
{

    /**
     * Builds an instance of @see PagesFacade.
     *
     * @param EventsDispatcherContract $eventsDispatcher
     * @param PagesRepositoryInterface $pagesRepository
     * @param PageVariantsSearcherInterface $pageVariantsSearcher
     * @param TagsFacade $tagsFacade
     * @return PagesFacade
     */
    public static function build(
        EventsDispatcherContract $eventsDispatcher,
        PagesRepositoryInterface $pagesRepository,
        PageVariantsSearcherInterface $pageVariantsSearcher,
        TagsFacade $tagsFacade
    ): PagesFacade {
        $pageVariantsValidator = new PageVariantsValidator();
        $pageVariantsRenderer = new PageVariantsRenderer();

        $pageVariantsCreator = new PageVariantsCreator($pageVariantsValidator, $tagsFacade);
        $pageVariantsUpdater = new PageVariantsUpdater($pageVariantsValidator, $tagsFacade);
        $pagesQuerier = new PageVariantsQuerier($pageVariantsSearcher);

        $pagesCreator = new PagesCreator($eventsDispatcher, $pagesRepository, $pageVariantsCreator);
        $pagesUpdater = new PagesUpdater($eventsDispatcher, $pagesRepository, $pageVariantsCreator, $pageVariantsUpdater);

        return new PagesFacade(
            $pagesCreator,
            $pagesUpdater,
            $pagesQuerier,
            $pageVariantsRenderer
        );
    }

}