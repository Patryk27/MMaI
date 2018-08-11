<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTagsQuery;
use App\Tags\Queries\TagsQueryInterface;
use Illuminate\Support\Collection;
use LogicException;

class TagsQuerier
{

    /**
     * @var TagsSearcherInterface
     */
    private $tagsSearcher;

    /**
     * @param TagsSearcherInterface $tagsSearcher
     */
    public function __construct(
        TagsSearcherInterface $tagsSearcher
    ) {
        $this->tagsSearcher = $tagsSearcher;
    }

    /**
     * Returns all tags matching given query.
     *
     * @param TagsQueryInterface $query
     * @return Collection|Tag[]
     */
    public function query(TagsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof SearchTagsQuery:
                return $query->applyTo($this->tagsSearcher)->get();

            default:
                throw new LogicException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * Returns number of tags matching given query.
     *
     * @param TagsQueryInterface $query
     * @return int
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