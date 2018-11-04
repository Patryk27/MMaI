<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\AnalyticsFacade;
use App\Analytics\Models\Event;
use App\Application\Events\RequestServed;
use Illuminate\Contracts\Queue\ShouldQueue;

final class RequestServedListener implements ShouldQueue
{

    /**
     * @var AnalyticsFacade
     */
    private $analyticsFacade;

    /**
     * @param AnalyticsFacade $analyticsFacade
     */
    public function __construct(
        AnalyticsFacade $analyticsFacade
    ) {
        $this->analyticsFacade = $analyticsFacade;
    }

    /**
     * @param RequestServed $event
     * @return void
     */
    public function handle(RequestServed $event): void
    {
        $this->analyticsFacade->create(Event::TYPE_REQUEST_SERVED, [
            'request' => [
                'url' => $event->getRequestUrl(),
                // @todo geolocate request's client's IP
            ],

            'response' => [
                'statusCode' => $event->getResponseStatusCode(),
            ],
        ]);
    }

}
