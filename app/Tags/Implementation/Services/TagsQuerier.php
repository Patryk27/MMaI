<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use App\Tags\Queries\GetAllTagsQuery;
use App\Tags\Queries\GetTagByIdQuery;
use App\Tags\Queries\SearchTagsQuery;
use App\Tags\Queries\TagsQuery;
use Illuminate\Support\Collection;

class TagsQuerier
{

    /**
     * @var TagsRepository
     */
    private $tagsRepository;

    /**
     * @var TagsSearcher
     */
    private $tagsSearcher;

    /**
     * @param TagsRepository $tagsRepository
     * @param TagsSearcher $tagsSearcher
     */
    public function __construct(
        TagsRepository $tagsRepository,
        TagsSearcher $tagsSearcher
    ) {
        $this->tagsRepository = $tagsRepository;
        $this->tagsSearcher = $tagsSearcher;
    }

    /**
     * @param TagsQuery $query
     * @return Collection|Tag[]
     *
     * @throws TagException
     */
    public function query(TagsQuery $query): Collection
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
     * @param TagsQuery $query
     * @return int
     *
     * @throws TagException
     */
    public function count(TagsQuery $query): int
    {
        switch (true) {
            case $query instanceof SearchTagsQuery:
                return $query->applyTo($this->tagsSearcher)->count();

            default:
                return $this->query($query)->count();
        }
    }

}
