<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;

class TagsValidator
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
     *
     * @throws TagException
     */
    public function validate(Tag $tag): void
    {
        $this->assertHasName($tag);
        $this->assertHasLanguage($tag);
        $this->assertIsUnique($tag);
    }

    /**
     * @param Tag $tag
     * @return void
     *
     * @throws TagException
     */
    private function assertHasName(Tag $tag): void
    {
        if (strlen($tag->name) === 0) {
            throw new TagException('Tag must be assigned a name.');
        }
    }

    /**
     * @param Tag $tag
     * @return void
     *
     * @throws TagException
     */
    private function assertHasLanguage(Tag $tag): void
    {
        if (is_null($tag->language_id)) {
            throw new TagException('Tag must be assigned a language.');
        }
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

        if (isset($duplicatedTag) && $duplicatedTag->id !== $tag->id) {
            throw new TagException('Tag with such name already exists.');
        }
    }

}