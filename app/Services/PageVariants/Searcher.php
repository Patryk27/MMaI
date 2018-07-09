<?php

namespace App\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\PageVariant;
use App\Services\Core\SearcherInterface;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;

class Searcher implements SearcherInterface
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

    private const FIELD_MAPPING = [
        self::FIELD_ID => 'page_variants.id',
        self::FIELD_TITLE => 'page_variants.title',
        self::FIELD_STATUS => 'page_variants.status',

        self::FIELD_PAGE_ID => 'pages.id',
        self::FIELD_PAGE_TYPE => 'pages.type',

        self::FIELD_LANGUAGE_ID => 'languages.id',
        self::FIELD_LANGUAGE_NAME => 'languages.name',

        self::FIELD_ROUTE_URL => 'routes.url',
    ];

    /**
     * @var EloquentBuilder
     */
    private $builder;

    /**
     * @param PageVariant $pageVariant
     */
    public function __construct(
        PageVariant $pageVariant
    ) {
        $builder = $pageVariant->newQuery();
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

        $this->builder = $builder;
    }

    /**
     * @inheritdoc
     */
    public function search(string $search): void
    {
        if (strlen($search) === 0) {
            return;
        }

        $this->builder->where(function (EloquentBuilder $builder) use ($search) {
            $builder->where('page_variants.title', 'like', '%' . $search . '%');
            $builder->orWhere('routes.url', 'like', '%' . $search . '%');
        });
    }

    /**
     * @inheritDoc
     *
     * @throws AppException
     */
    public function filter(array $filters): void
    {
        foreach ($filters as $field => $value) {
            switch ($field) {
                // @todo think of some another way, utilizing field mapping

                case self::FIELD_STATUS:
                    $this->builder->where('page_variants.status', $value);
                    break;

                case self::FIELD_PAGE_TYPE:
                    $this->builder->where('pages.type', $value);
                    break;

                case self::FIELD_LANGUAGE_ID:
                    $this->builder->where('languages.id', $value);
                    break;

                default:
                    throw new AppException(
                        sprintf('Unsupported field [%s] for filtering.', $field)
                    );
            }
        }
    }

    /**
     * @inheritdoc
     *
     * @throws AppException
     */
    public function orderBy(string $field, bool $ascending): void
    {
        if (!array_has(self::FIELD_MAPPING, $field)) {
            throw new AppException(
                sprintf('Unsupported field [%s] for ordering.', $field)
            );
        }

        $this->builder->orderBy(self::FIELD_MAPPING[$field], $ascending ? 'asc' : 'desc');
    }

    /**
     * @inheritdoc
     */
    public function forPage(int $page, int $perPage): void
    {
        $this->builder->forPage($page, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function get(): Collection
    {
        return $this->builder->get();
    }

    /**
     * @inheritdoc
     */
    public function getCount(): int
    {
        return $this->builder->count('page_variants.id');
    }

}