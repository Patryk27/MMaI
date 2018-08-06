<?php

namespace App\Tags\Implementation\Services\Searcher;

use App\Core\Exceptions\Exception as AppException;
use App\Core\Services\Searcher\AbstractEloquentSearcher;
use App\Core\Services\Searcher\EloquentSearcher;
use App\Tags\Implementation\Services\TagsSearcherInterface;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTagsQuery;

class EloquentTagsSearcher extends AbstractEloquentSearcher implements TagsSearcherInterface
{

    private const FIELDS_MAP = [
        SearchTagsQuery::FIELD_ID => 'tags.id',
        SearchTagsQuery::FIELD_NAME => 'tags.name',

        SearchTagsQuery::FIELD_LANGUAGE_ID => 'tags.language_id',
    ];

    /**
     * @param Tag $tag
     */
    public function __construct(
        Tag $tag
    ) {
        parent::__construct(
            new EloquentSearcher($tag, self::FIELDS_MAP)
        );
    }

    /**
     * @inheritDoc
     */
    public function search(string $search): void
    {
        $this->searcher->search($search, [
            SearchTagsQuery::FIELD_NAME,
        ]);
    }

    /**
     * @inheritDoc
     *
     * @throws AppException
     */
    public function filter(array $fields): void
    {
        $this->searcher->filter($fields, [
            SearchTagsQuery::FIELD_ID => EloquentSearcher::FILTER_EQUAL,
            SearchTagsQuery::FIELD_NAME => EloquentSearcher::FILTER_LIKE,

            SearchTagsQuery::FIELD_LANGUAGE_ID => EloquentSearcher::FILTER_EQUAL,
        ]);
    }

}