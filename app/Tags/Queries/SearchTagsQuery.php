<?php

namespace App\Tags\Queries;

use App\Core\Queries\AbstractSearchQuery;

/**
 * This class defines a query which will return all the tags matching given
 * criteria.
 */
final class SearchTagsQuery extends AbstractSearchQuery implements TagsQueryInterface
{

    public const
        FIELD_ID = 'id',
        FIELD_NAME = 'name',

        FIELD_LANGUAGE_ID = 'language_id',

        FIELD_PAGE_VARIANT_COUNT = 'page_variant_count';

}