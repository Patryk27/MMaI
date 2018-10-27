<?php

namespace App\Analytics\Queries;

use App\Core\Queries\AbstractSearchQuery;

abstract class AbstractSearchEventsQuery extends AbstractSearchQuery implements EventsQuery
{

    public const
        FIELD_ID = 'id',
        FIELD_TYPE = 'type',
        FIELD_CREATED_AT = 'created_at';

}
