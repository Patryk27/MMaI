<?php

namespace App\Tags;

use App\Core\Exceptions\Exception as AppException;
use App\Tags\Exceptions\TagNotFoundException;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Models\Tag;
use App\Tags\Queries\TagsQueryInterface;
use Illuminate\Support\Collection;

final class TagsFacade
{

    /**
     * @var TagsCreator
     */
    private $tagsCreator;

    /**
     * @var TagsQuerier
     */
    private $tagsQuerier;

    /**
     * @param TagsCreator $tagsCreator
     * @param TagsQuerier $tagsQuerier
     */
    public function __construct(
        TagsCreator $tagsCreator,
        TagsQuerier $tagsQuerier
    ) {
        $this->tagsCreator = $tagsCreator;
        $this->tagsQuerier = $tagsQuerier;
    }

    /**
     * Creates a new brand-new tag from given data.
     *
     * @see \App\App\Http\Requests\Backend\Tags\UpsertRequest
     *
     * @param array $tagData
     * @return Tag
     *
     * @throws AppException
     */
    public function create(array $tagData): Tag
    {
        return $this->tagsCreator->create($tagData);
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