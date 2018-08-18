<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;

class TagsUpdater
{

    /**
     * @var TagsRepositoryInterface
     */
    private $tagsRepository;

    /**
     * @var TagsValidator
     */
    private $tagsValidator;

    /**
     * @param TagsRepositoryInterface $tagsRepository
     * @param TagsValidator $tagsValidator
     */
    public function __construct(
        TagsRepositoryInterface $tagsRepository,
        TagsValidator $tagsValidator
    ) {
        $this->tagsRepository = $tagsRepository;
        $this->tagsValidator = $tagsValidator;
    }

    /**
     * @param Tag $tag
     * @param array $tagData
     * @return void
     *
     * @throws TagException
     */
    public function update(Tag $tag, array $tagData): void
    {
        if (isset($tagData['name'])) {
            $tag->name = $tagData['name'];
        }

        $this->tagsValidator->validate($tag);
        $this->tagsRepository->persist($tag);
    }

}