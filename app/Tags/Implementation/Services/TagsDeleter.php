<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagDeleted;
use App\Tags\Implementation\Repositories\TagsRepositoryInterface;
use App\Tags\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

class TagsDeleter
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
     * @param EventsDispatcherContract $eventsDispatcher
     * @param TagsRepositoryInterface $tagsRepository
     */
    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        TagsRepositoryInterface $tagsRepository
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->tagsRepository = $tagsRepository;
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function delete(Tag $tag): void
    {
        $this->tagsRepository->delete($tag);

        $this->eventsDispatcher->dispatch(
            new TagDeleted($tag)
        );
    }

}
