<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use App\Tags\Queries\GetAllTags;
use App\Tags\Queries\GetTagById;
use App\Tags\Queries\TagsQuery;
use Illuminate\Support\Collection;
use LogicException;

class TagsQuerier {

    /** @var TagsRepository */
    private $tagsRepository;

    public function __construct(TagsRepository $tagsRepository) {
        $this->tagsRepository = $tagsRepository;
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
                return collect_one($this->tagsRepository->getById($query->getId()));

            default:
                throw new LogicException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

}
