<?php

namespace App\Tags\Implementation\Services\Searcher;

use App\Core\Searcher\AbstractEloquentSearcher;
use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Tags\Implementation\Services\TagsSearcher;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTags;
use Illuminate\Database\Query\Builder as QueryBuilder;

class EloquentTagsSearcher extends AbstractEloquentSearcher implements TagsSearcher {

    private const FIELDS = [
        SearchTags::FIELD_ID => [
            'column' => 'tags.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchTags::FIELD_NAME => [
            'column' => 'tags.name',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],

        SearchTags::FIELD_CREATED_AT => [
            'column' => 'tags.created_at',
            'type' => EloquentMapper::FIELD_TYPE_DATETIME,
        ],

        SearchTags::FIELD_WEBSITE_ID => [
            'column' => 'tags.website_id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchTags::FIELD_ASSIGNED_PAGES_COUNT => [
            'column' => 'assigned_pages_count',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],
    ];

    public function __construct(Tag $tag) {
        parent::__construct($tag, self::FIELDS);

        $this->builder->selectRaw('tags.*');

        // Join number of pages per each tag
        $this->builder
            ->selectRaw('coalesce(pages_count.assigned_pages_count, 0) AS assigned_pages_count')
            ->leftJoinSub(function (QueryBuilder $builder): void {
                $builder
                    ->selectRaw('page_tag.tag_id AS tag_id')
                    ->selectRaw('count(page_tag.tag_id) AS assigned_pages_count')
                    ->from('page_tag')
                    ->groupBy('page_tag.tag_id');
            }, 'pages_count', 'pages_count.tag_id', 'tags.id');
    }

    /**
     * @inheritdoc
     */
    public function count(): int {
        return $this->get()->count(); // @todo provide a better implementation
    }

}
