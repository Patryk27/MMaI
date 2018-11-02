<?php

namespace App\Tags\Implementation\Services\Searcher;

use App\Core\Searcher\AbstractEloquentSearcher;
use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Tags\Implementation\Services\TagsSearcher;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTagsQuery;
use Illuminate\Database\Query\Builder as QueryBuilder;

class EloquentTagsSearcher extends AbstractEloquentSearcher implements TagsSearcher
{

    private const FIELDS = [
        SearchTagsQuery::FIELD_ID => [
            'column' => 'tags.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchTagsQuery::FIELD_NAME => [
            'column' => 'tags.name',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],

        SearchTagsQuery::FIELD_CREATED_AT => [
            'column' => 'tags.created_at',
            'type' => EloquentMapper::FIELD_TYPE_DATETIME,
        ],

        SearchTagsQuery::FIELD_LANGUAGE_ID => [
            'column' => 'tags.language_id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchTagsQuery::FIELD_PAGE_VARIANT_COUNT => [
            'column' => 'page_variant_count',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],
    ];

    /**
     * @param Tag $tag
     */
    public function __construct(
        Tag $tag
    ) {
        parent::__construct(
            $tag,
            self::FIELDS
        );

        $this->builder->selectRaw('tags.*');

        // Join number of pages per each tag
        // @todo this will be pretty slow on a big dataset set probably
        $this->builder
            ->selectRaw('page_variant_counts.page_variant_count')
            ->joinSub(function (QueryBuilder $builder): void {
                $builder
                    ->selectRaw('page_variant_tag.tag_id AS tag_id')
                    ->selectRaw('count(page_variant_tag.tag_id) AS page_variant_count')
                    ->from('page_variant_tag')
                    ->groupBy('page_variant_tag.tag_id');
            }, 'page_variant_counts', 'page_variant_counts.tag_id', 'tags.id');
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->get()->count(); // @todo provide a better implementation
    }

}
