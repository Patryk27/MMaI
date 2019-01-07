<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagUpdated;
use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

class TagsUpdater {
    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var TagsRepository */
    private $tagsRepository;

    /** @var TagsValidator */
    private $tagsValidator;

    public function __construct(
        EventsDispatcher $eventsDispatcher,
        TagsRepository $tagsRepository,
        TagsValidator $tagsValidator
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->tagsRepository = $tagsRepository;
        $this->tagsValidator = $tagsValidator;
    }

    /**
     * @param Tag $tag
     * @param array $tagData
     * @return void
     * @throws TagException
     */
    public function update(Tag $tag, array $tagData): void {
        if (array_has($tagData, 'name')) {
            $tag->name = $tagData['name'];
        }

        $this->tagsValidator->validate($tag);
        $this->tagsRepository->persist($tag);

        $this->eventsDispatcher->dispatch(
            new TagUpdated($tag)
        );
    }
}
