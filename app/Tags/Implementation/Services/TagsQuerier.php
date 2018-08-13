<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;
use App\Tags\Queries\GetAllTagsQuery;
use App\Tags\Queries\GetTagByIdQuery;
use App\Tags\Queries\SearchTagsQuery;
use App\Tags\Queries\TagsQueryInterface;
use Illuminate\Support\Collection;

class TagsQuerier
{

    /**
     * @var TagsRepositoryInterface
     */
    private $tagsRepository;

    /**
     * @var TagsSearcherInterface
     */
    private $tagsSearcher;

    /**
     * @param TagsRepositoryInterface $tagsRepository
     * @param TagsSearcherInterface $tagsSearcher
     */
    public function __construct(
        TagsRepositoryInterface $tagsRepository,
        TagsSearcherInterface $tagsSearcher
    ) {
        $this->tagsRepository = $tagsRepository;
        $this->tagsSearcher = $tagsSearcher;
    }

    /**
     * @param TagsQueryInterface $query
     * @return Collection|Tag[]
     *
     * @throws TagException
     */
    public function query(TagsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetAllTagsQuery:
                return $this->tagsRepository->getAll();

            case $query instanceof GetTagByIdQuery:
                return collect_one(
                    $this->tagsRepository->getById(
                        $query->getId()
                    )
                );

            case $query instanceof SearchTagsQuery:
                return $query->applyTo($this->tagsSearcher)->get();

            default:
                throw new TagException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * Returns number of tags matching given query.
     *
     * @param TagsQueryInterface $query
     * @return int
     *
     * @throws TagException
     */
    public function queryCount(TagsQueryInterface $query): int
    {
        switch (true) {
            case $query instanceof SearchTagsQuery:
                return $query->applyTo($this->tagsSearcher)->getCount();

            default:
                return $this->query($query)->count();
        }
    }

}