<?php

namespace App\Tags\Queries;

use App\Core\Queries\AbstractSearchQuery;

/**
 * This class defines a query which will return all the tags matching given
 * criteria.
 */
final class SearchTagsQuery extends AbstractSearchQuery implements TagsQuery
{

    public const
        FIELD_ID = 'id',
        FIELD_NAME = 'name',
        FIELD_CREATED_AT = 'createdAt',

        FIELD_LANGUAGE_ID = 'languageId',

        FIELD_PAGE_VARIANT_COUNT = 'pageVariantCount';

}
