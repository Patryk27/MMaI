<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;

class TagsDeleter
{

    /**
     * @var TagsRepositoryInterface
     */
    private $tagsRepository;

    /**
     * @param TagsRepositoryInterface $tagsRepository
     */
    public function __construct(
        TagsRepositoryInterface $tagsRepository
    ) {
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function delete(Tag $tag): void
    {
        $this->tagsRepository->delete($tag);
    }

}