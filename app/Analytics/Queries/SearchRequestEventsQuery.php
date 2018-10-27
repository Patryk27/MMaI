<?php

namespace App\Analytics\Queries;

/**
 * This class defines a query which will return all the events that are related
 * to requests.
 *
 * @see \App\Analytics\Models\Event::TYPE_REQUEST_FAILED
 * @see \App\Analytics\Models\Event::TYPE_REQUEST_SERVED
 */
final class SearchRequestEventsQuery extends AbstractSearchEventsQuery implements EventsQuery
{

    public const
        FIELD_URL = 'url';

}
