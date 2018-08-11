<?php

namespace App\Pages\Implementation\Services\PageVariants\Searcher;

use App\Core\Exceptions\Exception as AppException;
use App\Core\Services\Searcher\AbstractEloquentSearcher;
use App\Core\Services\Searcher\EloquentSearcher;
use App\Pages\Implementation\Services\PageVariants\PageVariantsSearcherInterface;
use App\Pages\Models\PageVariant;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Database\Query\JoinClause;

class EloquentPageVariantsSearcher extends AbstractEloquentSearcher implements PageVariantsSearcherInterface
{

    private const FIELDS_MAP = [
        SearchPageVariantsQuery::FIELD_ID => 'page_variants.id',
        SearchPageVariantsQuery::FIELD_TITLE => 'page_variants.title',
        SearchPageVariantsQuery::FIELD_STATUS => 'page_variants.status',
        SearchPageVariantsQuery::FIELD_CREATED_AT => 'page_variants.created_at',

        SearchPageVariantsQuery::FIELD_PAGE_ID => 'pages.id',
        SearchPageVariantsQuery::FIELD_PAGE_TYPE => 'pages.type',

        SearchPageVariantsQuery::FIELD_LANGUAGE_ID => 'languages.id',
        SearchPageVariantsQuery::FIELD_LANGUAGE_NAME => 'languages.english_name',

        SearchPageVariantsQuery::FIELD_ROUTE_URL => 'routes.url',
    ];

    /**
     * @param PageVariant $pageVariant
     */
    public function __construct(
        PageVariant $pageVariant
    ) {
        parent::__construct(
            new EloquentSearcher($pageVariant, self::FIELDS_MAP)
        );

        $builder = $this->searcher->getBuilder();
        $builder->selectRaw('page_variants.*');

        // Include pages
        $builder->join('pages', 'pages.id', 'page_variants.page_id');

        // Include languages
        $builder->join('languages', 'languages.id', 'page_variants.language_id');

        // Include routes
        $builder->leftJoin('routes', function (JoinClause $join): void {
            $join->on('routes.model_id', 'page_variants.id');

            // We have to do whereRaw() here instead of regular where() here,
            // because when using bindings, MySQL fails to prove functional
            // dependency between 'posts' and 'routes' tables, which causes some
            // search cases to fail (e.g. when sorting by routes):
            $join->whereRaw(
                sprintf('routes.model_type = "%s"', PageVariant::getMorphableType())
            );
        });
    }

    /**
     * @inheritdoc
     */
    public function search(string $search): void
    {
        $this->searcher->search($search, [
            SearchPageVariantsQuery::FIELD_TITLE,
            SearchPageVariantsQuery::FIELD_ROUTE_URL,
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
            SearchPageVariantsQuery::FIELD_ID => EloquentSearcher::FILTER_EQUAL,
            SearchPageVariantsQuery::FIELD_TITLE => EloquentSearcher::FILTER_LIKE,
            SearchPageVariantsQuery::FIELD_STATUS => EloquentSearcher::FILTER_EQUAL,

            SearchPageVariantsQuery::FIELD_PAGE_ID => EloquentSearcher::FILTER_EQUAL,
            SearchPageVariantsQuery::FIELD_PAGE_TYPE => EloquentSearcher::FILTER_EQUAL,

            SearchPageVariantsQuery::FIELD_LANGUAGE_ID => EloquentSearcher::FILTER_EQUAL,
            SearchPageVariantsQuery::FIELD_LANGUAGE_NAME => EloquentSearcher::FILTER_LIKE,

            SearchPageVariantsQuery::FIELD_ROUTE_URL => EloquentSearcher::FILTER_LIKE,
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getCount(): int
    {
        return $this->searcher
            ->getBuilder()
            ->count('page_variants.id');
    }

}