<?php

namespace App\Tags;

use App\Tags\Exceptions\TagNotFoundException;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Models\Tag;
use App\Tags\Queries\TagsQueryInterface;
use Illuminate\Support\Collection;

final class TagsFacade
{

    /**
     * @var TagsQuerier
     */
    private $tagsQuerier;

    /**
     * @param TagsQuerier $tagsQuerier
     */
    public function __construct(
        TagsQuerier $tagsQuerier
    ) {
        $this->tagsQuerier = $tagsQuerier;
    }

    /**
     * Creates a new brand-new tag from given data.
     *
     * @param array $tagData
     * @return Tag
     */
    public function create(array $tagData): Tag
    {
        unimplemented();
    }

    /**
     * Returns first tag matching given query.
     *
     * @param TagsQueryInterface $query
     * @return Tag
     *
     * @throws TagNotFoundException
     */
    public function queryOne(TagsQueryInterface $query): Tag
    {
        $tags = $this->queryMany($query);

        if ($tags->isEmpty()) {
            throw new TagNotFoundException();
        }

        return $tags->first();
    }

    /**
     * Returns all tags matching given query.
     *
     * @param TagsQueryInterface $query
     * @return Collection|Tag[]
     */
    public function queryMany(TagsQueryInterface $query): Collection
    {
        return $this->tagsQuerier->query($query);
    }

    /**
     * Returns number of tags matching given query.
     *
     * @param TagsQueryInterface $query
     * @return int
     */
    public function queryCount(TagsQueryInterface $query): int
    {
        return $this->tagsQuerier->queryCount($query);
    }

}