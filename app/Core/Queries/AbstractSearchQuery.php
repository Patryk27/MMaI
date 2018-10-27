<?php

namespace App\Core\Queries;

use App\Core\Searcher\Searcher;

/**
 * This is a base class which is used to facilitate building "searching"
 * queries, e.g. @see \App\Pages\Queries\SearchPageVariantsQuery.
 */
abstract class AbstractSearchQuery
{

    /**
     * @see Searcher::applyTextQuery()
     *
     * @var string
     */
    private $textQuery;

    /**
     * @see Searcher::applyFilters()
     *
     * @var array
     */
    private $filters;

    /**
     * @see Searcher::orderBy()
     *
     * @var array
     */
    private $orderBy;

    /**
     * @see Searcher::paginate()
     *
     * @var array
     */
    private $pagination;

    /**
     * @param array $query
     */
    public function __construct(
        array $query
    ) {
        $this->textQuery = array_get($query, 'textQuery', '');
        $this->filters = array_get($query, 'filters', []);
        $this->orderBy = array_get($query, 'orderBy', []);
        $this->pagination = array_get($query, 'pagination', []);
    }

    /**
     * Applies this query to given searcher and returns it.
     *
     * @param Searcher $searcher
     * @return Searcher
     */
    public function applyTo(Searcher $searcher): Searcher
    {
        $searcher->applyTextQuery(
            $this->getTextQuery()
        );

        $searcher->applyFilters(
            $this->getFilters()
        );

        foreach ($this->getOrderBy() as $fieldName => $fieldDirection) {
            $searcher->orderBy($fieldName, strtolower($fieldDirection) === 'asc');
        }

        if ($this->hasPagination()) {
            $searcher->paginate(
                $this->getPagination()['page'],
                $this->getPagination()['perPage']
            );
        }

        return $searcher;
    }

    /**
     * @return string
     */
    public function getTextQuery(): string
    {
        return $this->textQuery;
    }

    /**
     * @return array
     */
    public function getFilters(): array
    {
        return $this->filters;
    }

    /**
     * @return array
     */
    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    /**
     * @return bool
     */
    public function hasPagination(): bool
    {
        return !empty($this->pagination);
    }

    /**
     * @return array
     */
    public function getPagination(): array
    {
        return $this->pagination;
    }

}
