<?php

namespace App\Tags;

use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsDeleter;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Implementation\Services\TagsSearcherInterface;
use App\Tags\Implementation\Services\TagsUpdater;
use App\Tags\Implementation\Services\TagsValidator;

final class TagsFactory
{

    /**
     * Builds an instance of @see TagsFacade.
     *
     * @param TagsRepositoryInterface $tagsRepository
     * @param TagsSearcherInterface $tagsSearcher
     * @return TagsFacade
     */
    public static function build(
        TagsRepositoryInterface $tagsRepository,
        TagsSearcherInterface $tagsSearcher
    ): TagsFacade {
        $tagsValidator = new TagsValidator($tagsRepository);

        $tagsCreator = new TagsCreator($tagsRepository, $tagsValidator);
        $tagsUpdater = new TagsUpdater($tagsRepository, $tagsValidator);
        $tagsDeleter = new TagsDeleter($tagsRepository);
        $tagsQuerier = new TagsQuerier($tagsRepository, $tagsSearcher);

        return new TagsFacade(
            $tagsCreator,
            $tagsUpdater,
            $tagsDeleter,
            $tagsQuerier
        );
    }

}