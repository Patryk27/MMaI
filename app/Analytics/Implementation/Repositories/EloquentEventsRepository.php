<?php

namespace App\Analytics\Implementation\Repositories;

use App\Analytics\Models\Event;
use App\Core\Repositories\EloquentRepository;
use Throwable;

class EloquentEventsRepository implements EventsRepository
{
    /** @var EloquentRepository */
    private $repository;

    public function __construct(
        Event $event
    ) {
        $this->repository = new EloquentRepository($event);
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function persist(Event $event): void
    {
        $this->repository->persist($event);
    }
}
