<?php

namespace App\Analytics\Implementation\Services;

use App\Analytics\Exceptions\AnalyticsException;
use App\Analytics\Models\Event;
use App\Analytics\Queries\EventsQuery;
use App\Analytics\Queries\SearchRequestEventsQuery;
use Illuminate\Support\Collection;

class EventsQuerier
{

    /**
     * @var RequestEventsSearcher
     */
    private $requestEventsSearcher;

    /**
     * @param RequestEventsSearcher $requestEventsQuerier
     */
    public function __construct(
        RequestEventsSearcher $requestEventsQuerier
    ) {
        $this->requestEventsSearcher = $requestEventsQuerier;
    }

    /**
     * @param EventsQuery $query
     * @return Collection|Event[]
     *
     * @throws AnalyticsException
     */
    public function query(EventsQuery $query): Collection
    {
        switch (true) {
            case $query instanceof SearchRequestEventsQuery:
                return $query->applyTo($this->requestEventsSearcher)->get();

            default:
                throw new AnalyticsException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

    /**
     * @param EventsQuery $query
     * @return int
     *
     * @throws AnalyticsException
     */
    public function count(EventsQuery $query): int
    {
        switch (true) {
            case $query instanceof SearchRequestEventsQuery:
                return $query->applyTo($this->requestEventsSearcher)->count();

            default:
                return $this->query($query)->count();
        }
    }

}
