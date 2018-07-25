<?php

namespace App\Pages\Internal\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\PageVariant;
use App\Services\Core\Searcher\AbstractSearcher;
use App\Services\Core\Searcher\GenericSearcher;
use App\Services\Core\Searcher\SearcherInterface;
use Illuminate\Database\Query\JoinClause;

class PageVariantsSearcher extends AbstractSearcher implements SearcherInterface
{

    public const
        FIELD_ID = 'id',
        FIELD_TITLE = 'title',
        FIELD_STATUS = 'status',

        FIELD_PAGE_ID = 'page-id',
        FIELD_PAGE_TYPE = 'page-type',

        FIELD_LANGUAGE_ID = 'language-id',
        FIELD_LANGUAGE_NAME = 'language-name',

        FIELD_ROUTE_URL = 'route-url';

    private const FIELDS_MAP = [
        self::FIELD_ID => 'page_variants.id',
        self::FIELD_TITLE => 'page_variants.title',
        self::FIELD_STATUS => 'page_variants.status',

        self::FIELD_PAGE_ID => 'pages.id',
        self::FIELD_PAGE_TYPE => 'pages.type',

        self::FIELD_LANGUAGE_ID => 'languages.id',
        self::FIELD_LANGUAGE_NAME => 'languages.english_name',

        self::FIELD_ROUTE_URL => 'routes.url',
    ];

    /**
     * @param PageVariant $pageVariant
     */
    public function __construct(
        PageVariant $pageVariant
    ) {
        parent::__construct(
            new GenericSearcher($pageVariant, self::FIELDS_MAP)
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
            self::FIELD_TITLE,
            self::FIELD_ROUTE_URL,
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
            self::FIELD_STATUS => GenericSearcher::FILTER_OP_EQUAL,
            self::FIELD_PAGE_TYPE => GenericSearcher::FILTER_OP_EQUAL,
            self::FIELD_LANGUAGE_ID => GenericSearcher::FILTER_OP_EQUAL,
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