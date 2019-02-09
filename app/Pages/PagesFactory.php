<?php

namespace App\Pages;

use App\Attachments\AttachmentsFacade;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Implementation\Services\PagesModifier;
use App\Pages\Implementation\Services\PagesQuerier;
use App\Pages\Implementation\Services\PagesRenderer;
use App\Pages\Implementation\Services\PagesSearcher;
use App\Pages\Implementation\Services\PagesValidator;
use App\Tags\TagsFacade;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

final class PagesFactory {

    public static function build(
        EventsDispatcher $eventsDispatcher,
        PagesRepository $pagesRepository,
        PagesSearcher $pagesSearcher,
        AttachmentsFacade $attachmentsFacade,
        TagsFacade $tagsFacade
    ): PagesFacade {
        $pagesValidator = new PagesValidator();

        $pagesModifier = new PagesModifier($eventsDispatcher, $pagesRepository, $pagesValidator, $attachmentsFacade, $tagsFacade);
        $pagesRenderer = new PagesRenderer();
        $pagesQuerier = new PagesQuerier($pagesRepository, $pagesSearcher);

        return new PagesFacade(
            $pagesModifier,
            $pagesRenderer,
            $pagesQuerier
        );
    }

}
