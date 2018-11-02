<?php

namespace App\Pages\Queries;

use App\Core\Queries\AbstractSearchQuery;

/**
 * This class defines a query which will return all the page variants matching
 * given criteria.
 */
final class SearchPageVariantsQuery extends AbstractSearchQuery implements PageVariantsQuery
{

    public const
        FIELD_ID = 'id',
        FIELD_TITLE = 'title',
        FIELD_STATUS = 'status',
        FIELD_CREATED_AT = 'createdAt',

        FIELD_PAGE_ID = 'pageId',
        FIELD_PAGE_TYPE = 'pageType',

        FIELD_LANGUAGE_ID = 'languageId',
        FIELD_LANGUAGE_NAME = 'languageName',

        FIELD_ROUTE_URL = 'routeUrl';

}
