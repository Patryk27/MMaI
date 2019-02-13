<?php

namespace App\Tags;

use App\Tags\Exceptions\TagNotFoundException;
use App\Tags\Implementation\Services\TagsCreator;
use App\Tags\Implementation\Services\TagsDeleter;
use App\Tags\Implementation\Services\TagsQuerier;
use App\Tags\Implementation\Services\TagsUpdater;
use App\Tags\Models\Tag;
use App\Tags\Queries\TagsQuery;
use App\Tags\Requests\CreateTag;
use App\Tags\Requests\UpdateTag;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;

final class TagsFacade {

    /** @var TagsCreator */
    private $tagsCreator;

    /** @var TagsUpdater */
    private $tagsUpdater;

    /** @var TagsDeleter */
    private $tagsDeleter;

    /** @var TagsQuerier */
    private $tagsQuerier;

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
     * @param CreateTag $request
     * @return Tag
     * @throws ValidationException
     */
    public function create(CreateTag $request): Tag {
        return $this->tagsCreator->create($request);
    }

    /**
     * Updates an already existing tag.
     *
     * @param Tag $tag
     * @param UpdateTag $request
     * @throws ValidationException
     */
    public function update(Tag $tag, UpdateTag $request): void {
        $this->tagsUpdater->update($tag, $request);
    }

    /**
     * Removes given tag.
     * All pages assigned to this tag will be un-assigned from it.
     *
     * @param Tag $tag
     * @return void
     */
    public function delete(Tag $tag): void {
        $this->tagsDeleter->delete($tag);
    }

    /**
     * Returns the first tag matching given query.
     * Throws an exception if no such tag exists.
     *
     * @param TagsQuery $query
     * @return Tag
     * @throws TagNotFoundException
     */
    public function queryOne(TagsQuery $query): Tag {
        $tags = $this->queryMany($query);

        if ($tags->isEmpty()) {
            throw new TagNotFoundException();
        }

        return $tags->first();
    }

    /**
     * Returns all tags matching given query.
     *
     * @param TagsQuery $query
     * @return Collection|Tag[]
     */
    public function queryMany(TagsQuery $query): Collection {
        return $this->tagsQuerier->query($query);
    }

    /**
     * Returns number of tags matching given query.
     *
     * @param TagsQuery $query
     * @return int
     */
    public function queryCount(TagsQuery $query): int {
        return $this->tagsQuerier->count($query);
    }

}
