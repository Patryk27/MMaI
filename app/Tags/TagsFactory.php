<?php

namespace App\Tags;

use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsDeleter;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Implementation\Services\TagsSearcherInterface;

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
        $tagsCreator = new TagsCreator($tagsRepository);
        $tagsDeleter = new TagsDeleter($tagsRepository);
        $tagsQuerier = new TagsQuerier($tagsRepository, $tagsSearcher);

        return new TagsFacade(
            $tagsCreator,
            $tagsDeleter,
            $tagsQuerier
        );
    }

}