<?php

namespace App\Analytics\Queries;

/**
 * This class defines a query which will return all the events that are related
 * to requests.
 *
 * @see \App\Analytics\Models\Event::TYPE_REQUEST_SERVED
 */
final class SearchRequestEventsQuery extends AbstractSearchEventsQuery implements EventsQuery
{
    public const
        FIELD_REQUEST_URL = 'requestUrl',
        FIELD_RESPONSE_STATUS_CODE = 'responseStatusCode';
}
