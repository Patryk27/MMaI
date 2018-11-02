<?php

namespace App\Pages\Implementation\Services\PageVariants\Searcher;

use App\Core\Searcher\AbstractEloquentSearcher;
use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Pages\Implementation\Services\PageVariants\PageVariantsSearcher;
use App\Pages\Models\PageVariant;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Database\Query\JoinClause;

class EloquentPageVariantsSearcher extends AbstractEloquentSearcher implements PageVariantsSearcher
{

    private const FIELDS = [
        SearchPageVariantsQuery::FIELD_ID => [
            'column' => 'page_variants.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchPageVariantsQuery::FIELD_TITLE => [
            'column' => 'page_variants.title',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],

        SearchPageVariantsQuery::FIELD_STATUS => [
            'column' => 'page_variants.status',
            'type' => EloquentMapper::FIELD_TYPE_ENUM,
        ],

        SearchPageVariantsQuery::FIELD_CREATED_AT => [
            'column' => 'page_variants.created_at',
            'type' => EloquentMapper::FIELD_TYPE_DATETIME,
        ],

        SearchPageVariantsQuery::FIELD_PAGE_ID => [
            'column' => 'pages.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchPageVariantsQuery::FIELD_PAGE_TYPE => [
            'column' => 'pages.type',
            'type' => EloquentMapper::FIELD_TYPE_ENUM,
        ],

        SearchPageVariantsQuery::FIELD_LANGUAGE_ID => [
            'column' => 'languages.id',
            'type' => EloquentMapper::FIELD_TYPE_NUMBER,
        ],

        SearchPageVariantsQuery::FIELD_LANGUAGE_NAME => [
            'column' => 'languages.english_name',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],

        SearchPageVariantsQuery::FIELD_ROUTE_URL => [
            'column' => 'routes.url',
            'type' => EloquentMapper::FIELD_TYPE_STRING,
        ],
    ];

    /**
     * @param PageVariant $pageVariant
     */
    public function __construct(
        PageVariant $pageVariant
    ) {
        parent::__construct(
            $pageVariant,
            self::FIELDS
        );

        $this->builder->selectRaw('page_variants.*');

        // Include pages
        $this->builder->join('pages', 'pages.id', 'page_variants.page_id');

        // Include languages
        $this->builder->join('languages', 'languages.id', 'page_variants.language_id');

        // Include routes
        $this->builder->leftJoin('routes', function (JoinClause $join): void {
            $join->on('routes.model_id', 'page_variants.id');

            // We have to do whereRaw() instead of regular where() here, because
            // - when using bindings - MySQL fails to prove functional
            // dependency between the 'pages' and 'routes' tables, which cause
            // queries to fail (e.g. when ordering by routes):
            $join->whereRaw(
                sprintf('routes.model_type = "%s"', PageVariant::getMorphableType())
            );
        });
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->builder->count('page_variants.id');
    }

}
