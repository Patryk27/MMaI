<?php

namespace App\Tags\Queries;

use App\Core\Queries\AbstractSearchQuery;

final class SearchTagsQuery extends AbstractSearchQuery implements TagsQuery
{
    public const
        FIELD_ID = 'id',
        FIELD_NAME = 'name',
        FIELD_CREATED_AT = 'createdAt',

        FIELD_WEBSITE_ID = 'websiteId',

        FIELD_ASSIGNED_PAGES_COUNT = 'assignedPagesCount';
}
