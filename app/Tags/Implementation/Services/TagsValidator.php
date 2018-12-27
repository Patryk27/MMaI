<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;

class TagsValidator
{
    /** @var TagsRepository */
    private $tagsRepository;

    public function __construct(TagsRepository $tagsRepository)
    {
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * @param Tag $tag
     * @return void
     * @throws TagException
     */
    public function validate(Tag $tag): void
    {
        $this->assertHasName($tag);
        $this->assertHasWebsite($tag);
        $this->assertIsUnique($tag);
    }

    /**
     * @param Tag $tag
     * @return void
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
     * @throws TagException
     */
    private function assertHasWebsite(Tag $tag): void
    {
        if (is_null($tag->website_id)) {
            throw new TagException('Tag must be assigned a website.');
        }
    }

    /**
     * @param Tag $tag
     * @return void
     * @throws TagException
     */
    private function assertIsUnique(Tag $tag): void
    {
        $duplicatedTag = $this->tagsRepository->getByWebsiteIdAndName($tag->website_id, $tag->name);

        if (isset($duplicatedTag) && $duplicatedTag->id !== $tag->id) {
            throw new TagException('Tag with such name already exists.');
        }
    }
}
