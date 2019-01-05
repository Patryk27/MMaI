<?php

namespace App\Pages\Queries;

use App\Core\Queries\AbstractSearchQuery;

final class SearchPages extends AbstractSearchQuery implements PagesQuery {
    public const
        FIELD_ID = 'id',
        FIELD_TITLE = 'title',
        FIELD_TYPE = 'type',
        FIELD_STATUS = 'status',
        FIELD_CREATED_AT = 'createdAt',

        FIELD_WEBSITE_ID = 'websiteId',
        FIELD_WEBSITE_NAME = 'websiteName',

        FIELD_ROUTE_URL = 'routeUrl';
}
