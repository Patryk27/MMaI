<?php

namespace App\Analytics;

use App\Analytics\Exceptions\AnalyticsException;
use App\Analytics\Implementation\Repositories\EventsRepository;
use App\Analytics\Implementation\Services\EventsBuilder;
use App\Analytics\Implementation\Services\EventsQuerier;
use App\Analytics\Models\Event;
use App\Analytics\Queries\EventsQuery;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;

final class AnalyticsFacade
{

    /**
     * @var EventsRepository
     */
    private $eventsRepository;

    /**
     * @var EventsBuilder
     */
    private $eventsBuilder;

    /**
     * @var EventsQuerier
     */
    private $eventsQuerier;

    /**
     * @param EventsRepository $eventsRepository
     * @param EventsBuilder $eventsBuilder
     * @param EventsQuerier $eventsQuerier
     */
    public function __construct(
        EventsRepository $eventsRepository,
        EventsBuilder $eventsBuilder,
        EventsQuerier $eventsQuerier
    ) {
        $this->eventsRepository = $eventsRepository;
        $this->eventsBuilder = $eventsBuilder;
        $this->eventsQuerier = $eventsQuerier;
    }

    /**
     * Saves a "request served" event to the log.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function logRequestServed(Request $request, Response $response): void
    {
        $this->eventsRepository->persist(
            $this->eventsBuilder->buildRequestServed($request, $response)
        );
    }

    /**
     * Returns the first event matching given query.
     * Throws an exception if no such event exists.
     *
     * @param EventsQuery $query
     * @return Event
     */
    public function queryOne(EventsQuery $query): Event
    {
        unimplemented();
    }

    /**
     * Returns all events matching given query.
     *
     * @param EventsQuery $query
     * @return Collection|Event[]
     *
     * @throws AnalyticsException
     */
    public function queryMany(EventsQuery $query): Collection
    {
        return $this->eventsQuerier->query($query);
    }

    /**
     * Returns number of events matching given query.
     *
     * @param EventsQuery $query
     * @return int
     *
     * @throws AnalyticsException
     */
    public function queryCount(EventsQuery $query): int
    {
        return $this->eventsQuerier->count($query);
    }

}
