<?php

namespace App\Pages;

use App\Attachments\AttachmentsFacade;
use App\Grid\GridFacade;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Implementation\Services\Grid\PagesGridQueryExecutor;
use App\Pages\Implementation\Services\Grid\PagesGridSchemaProvider;
use App\Pages\Implementation\Services\Grid\Sources\PagesGridSource;
use App\Pages\Implementation\Services\PagesModifier;
use App\Pages\Implementation\Services\PagesQuerier;
use App\Pages\Implementation\Services\PagesRenderer;
use App\Pages\Implementation\Services\PagesValidator;
use App\Tags\TagsFacade;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

final class PagesFactory {

    public static function build(
        EventsDispatcher $eventsDispatcher,
        PagesRepository $pagesRepository,
        PagesGridSource $pagesGridSource,
        AttachmentsFacade $attachmentsFacade,
        GridFacade $gridFacade,
        TagsFacade $tagsFacade,
        WebsitesFacade $websitesFacade
    ): PagesFacade {
        $pagesValidator = new PagesValidator();

        $pagesModifier = new PagesModifier($eventsDispatcher, $pagesRepository, $pagesValidator, $attachmentsFacade, $tagsFacade);
        $pagesRenderer = new PagesRenderer();
        $pagesQuerier = new PagesQuerier($pagesRepository);
        $pagesGridQueryExecutor = new PagesGridQueryExecutor($gridFacade, $pagesGridSource);
        $pagesGridSchemaProvider = new PagesGridSchemaProvider($websitesFacade);

        return new PagesFacade(
            $pagesModifier,
            $pagesRenderer,
            $pagesQuerier,
            $pagesGridQueryExecutor,
            $pagesGridSchemaProvider
        );
    }

}
