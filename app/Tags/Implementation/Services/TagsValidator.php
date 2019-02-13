<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use Illuminate\Validation\ValidationException;
use Validator;

class TagsValidator {

    /** @var TagsRepository */
    private $tagsRepository;

    public function __construct(TagsRepository $tagsRepository) {
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * @param Tag $tag
     * @return void
     * @throws ValidationException
     */
    public function validate(Tag $tag): void {
        $this->validateScalars($tag);
        $this->validateUniqueness($tag);
    }

    /**
     * @param Tag $tag
     * @return void
     * @throws ValidationException
     */
    private function validateScalars(Tag $tag): void {
        Validator::validate($tag->toArray(), [
            'name' => ['string', 'regex:/^[a-zA-Z\-]+$/'],
            'website_id' => 'integer',
        ]);
    }

    /**
     * @param Tag $tag
     * @return void
     */
    private function validateUniqueness(Tag $tag): void {
        $duplicatedTag = $this->tagsRepository->getByWebsiteIdAndName(
            $tag->website_id, $tag->name
        );

        if (isset($duplicatedTag) && $duplicatedTag->id !== $tag->id) {
            throw ValidationException::withMessages([
                'name' => 'This name has been taken.',
            ]);
        }
    }

}
