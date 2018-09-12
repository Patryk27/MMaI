<?php

namespace App\Tags;

use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsDeleter;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Implementation\Services\TagsSearcherInterface;
use App\Tags\Implementation\Services\TagsUpdater;
use App\Tags\Implementation\Services\TagsValidator;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

final class TagsFactory
{

    /**
     * Builds an instance of @see TagsFacade.
     *
     * @param EventsDispatcherContract $eventsDispatcher
     * @param TagsRepositoryInterface $tagsRepository
     * @param TagsSearcherInterface $tagsSearcher
     * @return TagsFacade
     */
    public static function build(
        EventsDispatcherContract $eventsDispatcher,
        TagsRepositoryInterface $tagsRepository,
        TagsSearcherInterface $tagsSearcher
    ): TagsFacade {
        $tagsValidator = new TagsValidator($tagsRepository);

        $tagsCreator = new TagsCreator($eventsDispatcher, $tagsRepository, $tagsValidator);
        $tagsUpdater = new TagsUpdater($eventsDispatcher, $tagsRepository, $tagsValidator);
        $tagsDeleter = new TagsDeleter($eventsDispatcher, $tagsRepository);
        $tagsQuerier = new TagsQuerier($tagsRepository, $tagsSearcher);

        return new TagsFacade(
            $tagsCreator,
            $tagsUpdater,
            $tagsDeleter,
            $tagsQuerier
        );
    }

}
