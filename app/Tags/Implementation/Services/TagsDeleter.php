<?php

namespace App\Tags\Implementation\Services;

use App\Tags\Events\TagDeleted;
use App\Tags\Implementation\Repositories\TagsRepository;
use App\Tags\Models\Tag;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

class TagsDeleter
{
    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var TagsRepository */
    private $tagsRepository;

    public function __construct(
        EventsDispatcher $eventsDispatcher,
        TagsRepository $tagsRepository
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
