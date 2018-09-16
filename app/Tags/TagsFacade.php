<?php

namespace App\Tags;

use App\Tags\Exceptions\TagException;
use App\Tags\Exceptions\TagNotFoundException;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsDeleter;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Implementation\Services\TagsUpdater;
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
     * @var TagsUpdater
     */
    private $tagsUpdater;

    /**
     * @var TagsDeleter
     */
    private $tagsDeleter;

    /**
     * @var TagsQuerier
     */
    private $tagsQuerier;

    /**
     * @param TagsCreator $tagsCreator
     * @param TagsUpdater $tagsUpdater
     * @param TagsDeleter $tagsDeleter
     * @param TagsQuerier $tagsQuerier
     */
    public function __construct(
        TagsCreator $tagsCreator,
        TagsUpdater $tagsUpdater,
        TagsDeleter $tagsDeleter,
        TagsQuerier $tagsQuerier
    ) {
        $this->tagsCreator = $tagsCreator;
        $this->tagsUpdater = $tagsUpdater;
        $this->tagsDeleter = $tagsDeleter;
        $this->tagsQuerier = $tagsQuerier;
    }

    /**
     * Creates a new brand-new tag from given data.
     *
     * @param array $tagData
     * @return Tag
     *
     * @throws TagException
     *
     * @see \App\Application\Http\Requests\Backend\Tags\CreateTagRequest
     * @see \Tests\Unit\Tags\CreateTest
     */
    public function create(array $tagData): Tag
    {
        return $this->tagsCreator->create($tagData);
    }

    /**
     * Updates an already existing tag.
     *
     * @param Tag $tag
     * @param array $tagData
     *
     * @throws TagException
     *
     * @see \App\Application\Http\Requests\Backend\Tags\UpdateTagRequest
     * @see \Tests\Unit\Tags\UpdateTest
     */
    public function update(Tag $tag, array $tagData): void
    {
        $this->tagsUpdater->update($tag, $tagData);
    }

    /**
     * Removes given tag.
     * All page variants assigned to this tag will be un-assigned from it.
     *
     * @param Tag $tag
     * @return void
     */
    public function delete(Tag $tag): void
    {
        $this->tagsDeleter->delete($tag);
    }

    /**
     * Returns the first tag matching given query.
     * Throws an exception if no such tag exists.
     *
     * @param TagsQueryInterface $query
     * @return Tag
     *
     * @throws TagException
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
     *
     * @throws TagException
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
     *
     * @throws TagException
     */
    public function queryCount(TagsQueryInterface $query): int
    {
        return $this->tagsQuerier->queryCount($query);
    }

}
