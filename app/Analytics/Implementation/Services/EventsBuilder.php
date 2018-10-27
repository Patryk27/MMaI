<?php

namespace App\Analytics\Implementation\Services;

use App\Analytics\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EventsBuilder
{

    /**
     * @param Request $request
     * @return Event
     */
    public function buildRequestServed(Request $request): Event
    {
        return new Event([
            'type' => Event::TYPE_REQUEST_SERVED,

            'payload' => [
                'url' => $request->fullUrl(),
                // @todo add country, route etc.
            ],
        ]);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @return Event
     */
    public function buildRequestFailed(Request $request, Response $response): Event
    {
        return new Event([
            'type' => Event::TYPE_REQUEST_FAILED,

            'payload' => [
                'url' => $request->fullUrl(),
                'status' => $response->status(),
            ],
        ]);
    }

}
