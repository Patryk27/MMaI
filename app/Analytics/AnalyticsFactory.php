<?php

namespace App\Analytics;

use App\Analytics\Implementation\Repositories\EventsRepository;
use App\Analytics\Implementation\Services\EventsQuerier;
use App\Analytics\Implementation\Services\RequestEventsSearcher;

final class AnalyticsFactory {

    public static function build(
        EventsRepository $eventsRepository,
        RequestEventsSearcher $requestEventsSearcher
    ): AnalyticsFacade {
        $eventsQuerier = new EventsQuerier($requestEventsSearcher);

        return new AnalyticsFacade($eventsRepository, $eventsQuerier);
    }

}
