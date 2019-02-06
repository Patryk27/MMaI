<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagUpdated;
use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use App\Tags\Requests\UpdateTag;
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
     * @param UpdateTag $request
     * @return void
     * @throws TagException
     */
    public function update(Tag $tag, UpdateTag $request): void {
        $tag->name = $request->get('name');

        $this->tagsValidator->validate($tag);
        $this->tagsRepository->persist($tag);
        $this->eventsDispatcher->dispatch(new TagUpdated($tag));
    }

}
