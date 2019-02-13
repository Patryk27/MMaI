<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagCreated;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use App\Tags\Requests\CreateTag;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Validation\ValidationException;

class TagsCreator {

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
     * @param CreateTag $request
     * @return Tag
     * @throws ValidationException
     */
    public function create(CreateTag $request): Tag {
        $tag = new Tag([
            'website_id' => $request->get('website_id'),
            'name' => $request->get('name'),
        ]);

        $this->tagsValidator->validate($tag);
        $this->tagsRepository->persist($tag);
        $this->eventsDispatcher->dispatch(new TagCreated($tag));

        return $tag;
    }

}
