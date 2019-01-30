<?php

namespace App\Analytics;

use App\Analytics\Exceptions\AnalyticsException;
use App\Analytics\Implementation\Listeners\LoginAttemptedListener;
use App\Analytics\Implementation\Listeners\QuerySearchedListener;
use App\Analytics\Implementation\Listeners\RequestServedListener;
use App\Analytics\Implementation\Repositories\EventsRepository;
use App\Analytics\Implementation\Services\EventsQuerier;
use App\Analytics\Models\Event;
use App\Analytics\Queries\EventsQuery;
use App\Application\Events\LoginAttempted;
use App\Application\Events\RequestServed;
use App\SearchEngine\Events\QueryPerformed;
use Event as EventFacade;
use Illuminate\Support\Collection;

final class AnalyticsFacade {

    /** @var EventsRepository */
    private $eventsRepository;

    /** @var EventsQuerier */
    private $eventsQuerier;

    public function __construct(
        EventsRepository $eventsRepository,
        EventsQuerier $eventsQuerier
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->eventsQuerier = $eventsQuerier;
    }

    /**
     * @return void
     */
    public function boot(): void {
        EventFacade::listen(LoginAttempted::class, LoginAttemptedListener::class);
        EventFacade::listen(QueryPerformed::class, QuerySearchedListener::class);
        EventFacade::listen(RequestServed::class, RequestServedListener::class);
    }

    /**
     * Creates and persists given event.
     *
     * @param string $eventType
     * @param array $eventPayload
     * @return Event
     */
    public function create(string $eventType, array $eventPayload) {
        $event = new Event([
            'type' => $eventType,
            'payload' => $eventPayload,
        ]);

        $this->eventsRepository->persist($event);

        return $event;
    }

    /**
     * Returns the first event matching given query.
     * Throws an exception if no such event exists.
     *
     * @param EventsQuery $query
     * @return Event
     */
    public function queryOne(EventsQuery $query): Event {
        unimplemented();
    }

    /**
     * Returns all events matching given query.
     *
     * @param EventsQuery $query
     * @return Collection|Event[]
     * @throws AnalyticsException
     */
    public function queryMany(EventsQuery $query): Collection {
        return $this->eventsQuerier->query($query);
    }

    /**
     * Returns number of events matching given query.
     *
     * @param EventsQuery $query
     * @return int
     * @throws AnalyticsException
     */
    public function queryCount(EventsQuery $query): int {
        return $this->eventsQuerier->count($query);
    }

}
