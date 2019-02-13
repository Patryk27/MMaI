<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use App\Tags\Queries\GetAllTags;
use App\Tags\Queries\GetTagById;
use App\Tags\Queries\SearchTags;
use App\Tags\Queries\TagsQuery;
use Illuminate\Support\Collection;
use LogicException;

class TagsQuerier {

    /** @var TagsRepository */
    private $tagsRepository;

    /** @var TagsSearcher */
    private $tagsSearcher;

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
     */
    public function query(TagsQuery $query): Collection {
        switch (true) {
            case $query instanceof GetAllTags:
                return $this->tagsRepository->getAll();

            case $query instanceof GetTagById:
                return collect_one(
                    $this->tagsRepository->getById(
                        $query->getId()
                    )
                );

            case $query instanceof SearchTags:
                return $query->applyTo($this->tagsSearcher)->get();

            default:
                throw new LogicException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

    /**
     * Returns number of tags matching given query.
     *
     * @param TagsQuery $query
     * @return int
     */
    public function count(TagsQuery $query): int {
        switch (true) {
            case $query instanceof SearchTags:
                return $query->applyTo($this->tagsSearcher)->count();

            default:
                return $this->query($query)->count();
        }
    }

}
