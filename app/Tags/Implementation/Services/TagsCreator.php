<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;

class TagsCreator
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
     * Creates a new brand-new tag from given data.
     *
     * @see \App\App\Http\Requests\Backend\Tags\UpsertRequest
     *
     * @param array $tagData
     * @return Tag
     *
     * @throws TagException
     */
    public function create(array $tagData): Tag
    {
        $tag = new Tag(
            array_only($tagData, ['language_id', 'name'])
        );

        $this->assertIsUnique($tag);
        $this->tagsRepository->persist($tag);

        return $tag;
    }

    /**
     * @param Tag $tag
     * @return void
     *
     * @throws TagException
     */
    private function assertIsUnique(Tag $tag): void
    {
        $duplicatedTag = $this->tagsRepository->getByLanguageIdAndName($tag->language_id, $tag->name);

        if (isset($duplicatedTag)) {
            throw new TagException('Tag with such name already exists.');
        }
    }

}