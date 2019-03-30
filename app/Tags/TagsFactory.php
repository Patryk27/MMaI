<?php

namespace App\Tags;

use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsDeleter;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Implementation\Services\TagsUpdater;
use App\Tags\Implementation\Services\TagsValidator;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

final class TagsFactory {

    public static function build(
        EventsDispatcher $eventsDispatcher,
        TagsRepository $tagsRepository
    ): TagsFacade {
        $tagsValidator = new TagsValidator($tagsRepository);

        $tagsCreator = new TagsCreator($eventsDispatcher, $tagsRepository, $tagsValidator);
        $tagsUpdater = new TagsUpdater($eventsDispatcher, $tagsRepository, $tagsValidator);
        $tagsDeleter = new TagsDeleter($eventsDispatcher, $tagsRepository);
        $tagsQuerier = new TagsQuerier($tagsRepository);

        return new TagsFacade(
            $tagsCreator,
            $tagsUpdater,
            $tagsDeleter,
            $tagsQuerier
        );
    }

}
