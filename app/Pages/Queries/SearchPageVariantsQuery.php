<?php

namespace App\Pages\Queries;

use App\Core\Queries\AbstractSearchQuery;

/**
 * This class defines a query which will return all the page variants matching
 * given query.
 */
final class SearchPageVariantsQuery extends AbstractSearchQuery implements PageVariantsQueryInterface
{

    public const
        FIELD_ID = 'id',
        FIELD_TITLE = 'title',
        FIELD_STATUS = 'status',

        FIELD_PAGE_ID = 'page-id',
        FIELD_PAGE_TYPE = 'page-type',

        FIELD_LANGUAGE_ID = 'language-id',
        FIELD_LANGUAGE_NAME = 'language-name',

        FIELD_ROUTE_URL = 'route-url';

}