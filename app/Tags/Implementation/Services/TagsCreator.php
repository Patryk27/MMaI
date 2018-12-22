<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagCreated;
use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

class TagsCreator
{
    /** @var EventsDispatcherContract */
    private $eventsDispatcher;

    /** @var TagsRepository */
    private $tagsRepository;

    /** @var TagsValidator */
    private $tagsValidator;

    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        TagsRepository $tagsRepository,
        TagsValidator $tagsValidator
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->tagsRepository = $tagsRepository;
        $this->tagsValidator = $tagsValidator;
    }

    /**
     * @param array $tagData
     * @return Tag
     * @throws TagException
     */
    public function create(array $tagData): Tag
    {
        $tag = new Tag(
            array_only($tagData, ['language_id', 'name'])
        );

        $this->tagsValidator->validate($tag);
        $this->tagsRepository->persist($tag);

        $this->eventsDispatcher->dispatch(
            new TagCreated($tag)
        );

        return $tag;
    }
}
