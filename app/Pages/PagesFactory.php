<?php

namespace App\Pages;

use App\Attachments\AttachmentsFacade;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Implementation\Services\PagesCreator;
use App\Pages\Implementation\Services\PagesQuerier;
use App\Pages\Implementation\Services\PagesRenderer;
use App\Pages\Implementation\Services\PagesSearcher;
use App\Pages\Implementation\Services\PagesUpdater;
use App\Pages\Implementation\Services\PagesValidator;
use App\Tags\TagsFacade;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

final class PagesFactory
{
    /**
     * Builds an instance of @see PagesFacade.
     *
     * @param EventsDispatcher $eventsDispatcher
     * @param PagesRepository $pagesRepository
     * @param PagesSearcher $pagesSearcher
     * @param AttachmentsFacade $attachmentsFacade
     * @param TagsFacade $tagsFacade
     * @return PagesFacade
     */
    public static function build(
        EventsDispatcher $eventsDispatcher,
        PagesRepository $pagesRepository,
        PagesSearcher $pagesSearcher,
        AttachmentsFacade $attachmentsFacade,
        TagsFacade $tagsFacade
    ): PagesFacade {
        $pagesValidator = new PagesValidator();

        $pagesCreator = new PagesCreator($eventsDispatcher, $pagesRepository, $pagesValidator, $attachmentsFacade);
        $pagesUpdater = new PagesUpdater();
        $pagesRenderer = new PagesRenderer();
        $pagesQuerier = new PagesQuerier($pagesRepository, $pagesSearcher);

        return new PagesFacade(
            $pagesCreator,
            $pagesUpdater,
            $pagesRenderer,
            $pagesQuerier
        );
    }
}
