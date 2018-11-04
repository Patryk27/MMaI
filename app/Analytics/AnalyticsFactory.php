<?php

namespace App\Analytics;

use App\Analytics\Implementation\Repositories\EventsRepository;
use App\Analytics\Implementation\Services\EventsQuerier;
use App\Analytics\Implementation\Services\RequestEventsSearcher;

final class AnalyticsFactory
{

    /**
     * Builds an instance of @see AnalyticsFacade.
     *
     * @param EventsRepository $eventsRepository
     * @param RequestEventsSearcher $requestEventsSearcher
     * @return AnalyticsFacade
     */
    public static function build(
        EventsRepository $eventsRepository,
        RequestEventsSearcher $requestEventsSearcher
    ): AnalyticsFacade {
        $eventsQuerier = new EventsQuerier($requestEventsSearcher);

        return new AnalyticsFacade(
            $eventsRepository,
            $eventsQuerier
        );
    }

}
