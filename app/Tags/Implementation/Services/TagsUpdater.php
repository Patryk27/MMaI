<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagUpdated;
use App\Tags\Exceptions\TagException;
use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

class TagsUpdater
{

    /**
     * @var EventsDispatcherContract
     */
    private $eventsDispatcher;

    /**
     * @var TagsRepositoryInterface
     */
    private $tagsRepository;

    /**
     * @var TagsValidator
     */
    private $tagsValidator;

    /**
     * @param EventsDispatcherContract $eventsDispatcher
     * @param TagsRepositoryInterface $tagsRepository
     * @param TagsValidator $tagsValidator
     */
    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        TagsRepositoryInterface $tagsRepository,
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

        $this->eventsDispatcher->dispatch(
            new TagUpdated($tag)
        );
    }

}
