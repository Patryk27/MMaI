<?php

namespace App\Pages\Queries;

use App\Core\Queries\AbstractSearchQuery;

final class SearchPages extends AbstractSearchQuery implements PageQuery
{
    public const
        FIELD_ID = 'id',
        FIELD_TITLE = 'title',
        FIELD_TYPE = 'type',
        FIELD_STATUS = 'status',
        FIELD_CREATED_AT = 'createdAt',

        FIELD_LANGUAGE_ID = 'languageId',
        FIELD_LANGUAGE_NAME = 'languageName',

        FIELD_ROUTE_URL = 'routeUrl';
}
