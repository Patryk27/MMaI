<?php

namespace App\Pages\Queries;

use App\Core\Queries\AbstractSearchQuery;

/**
 * This class defines a query which will return all the page variants matching
 * given criteria.
 */
final class SearchPageVariantsQuery extends AbstractSearchQuery implements PageVariantsQueryInterface
{

    public const
        FIELD_ID = 'id',
        FIELD_TITLE = 'title',
        FIELD_STATUS = 'status',

        FIELD_PAGE_ID = 'page_id',
        FIELD_PAGE_TYPE = 'page_type',

        FIELD_LANGUAGE_ID = 'language_id',
        FIELD_LANGUAGE_NAME = 'language_name',

        FIELD_ROUTE_URL = 'route_url';

}