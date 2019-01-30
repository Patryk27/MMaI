<?php

namespace App\Pages\Implementation\Services\Searcher;

use App\Core\Searcher\AbstractEloquentSearcher;
use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Pages\Implementation\Services\PagesSearcher;
use App\Pages\Models\Page;
use App\Pages\Queries\SearchPages;
use Illuminate\Database\Query\JoinClause;

class EloquentPagesSearcher extends AbstractEloquentSearcher implements PagesSearcher {

    private const FIELDS = [
        SearchPages::FIELD_ID => [
            'column' => 'pages.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchPages::FIELD_TITLE => [
            'column' => 'pages.title',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],

        SearchPages::FIELD_TYPE => [
            'column' => 'pages.type',
            'type' => EloquentMapper::FIELD_TYPE_ENUM,
        ],

        SearchPages::FIELD_STATUS => [
            'column' => 'pages.status',
            'type' => EloquentMapper::FIELD_TYPE_ENUM,
        ],

        SearchPages::FIELD_CREATED_AT => [
            'column' => 'pages.created_at',
            'type' => EloquentMapper::FIELD_TYPE_DATETIME,
        ],

        SearchPages::FIELD_WEBSITE_ID => [
            'column' => 'websites.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchPages::FIELD_WEBSITE_NAME => [
            'column' => 'websites.name',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],

        SearchPages::FIELD_ROUTE_URL => [
            'column' => 'routes.url',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],
    ];

    /**
     * @param Page $page
     */
    public function __construct(Page $page) {
        parent::__construct($page, self::FIELDS);

        $this->builder->selectRaw('pages.*');

        // Include websites
        $this->builder->join('websites', 'websites.id', 'pages.website_id');

        // Include routes
        $this->builder->leftJoin('routes', function (JoinClause $join): void {
            $join->on('routes.model_id', 'pages.id');

            // We have to do whereRaw() instead of regular where() here, because
            // when using bindings MySQL fails to prove functional dependency
            // between the 'pages' and 'routes' tables, which causes this query
            // to fail in some cases (e.g. when ordering by routes):
            $join->whereRaw(sprintf(
                'routes.model_type = \'%s\'', Page::getMorphableType()
            ));
        });
    }

    /**
     * @inheritdoc
     */
    public function count(): int {
        return $this->builder->count('pages.id');
    }

}
