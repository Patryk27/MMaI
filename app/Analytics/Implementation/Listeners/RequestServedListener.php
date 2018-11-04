<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\Models\Event;
use App\Application\Events\RequestServed;

final class RequestServedListener extends Listener
{

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
