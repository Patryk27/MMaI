<?php

namespace App\Tags\Queries;

use App\Core\Queries\AbstractSearchQuery;

final class SearchTagsQuery extends AbstractSearchQuery implements TagsQuery
{
    public const
        FIELD_ID = 'id',
        FIELD_NAME = 'name',
        FIELD_CREATED_AT = 'createdAt',

        FIELD_LANGUAGE_ID = 'languageId',

        FIELD_ASSIGNED_PAGES_COUNT = 'assignedPagesCount';
}
