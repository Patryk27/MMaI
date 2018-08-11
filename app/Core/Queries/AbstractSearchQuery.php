<?php

namespace App\Core\Queries;

use App\Core\Services\Searcher\SearcherInterface;

/**
 * This is a base class which is used to facilitate building "searching"
 * queries, e.g. @see \App\Pages\Queries\SearchPageVariantsQuery.
 */
abstract class AbstractSearchQuery
{

    /**
     * @see \App\Core\Services\Searcher\SearcherInterface::search()
     *
     * @var string
     */
    private $search;

    /**
     * @see \App\Core\Services\Searcher\SearcherInterface::filter()
     *
     * @var array
     */
    private $filters;

    /**
     * @see \App\Core\Services\Searcher\SearcherInterface::orderBy()
     *
     * @var array
     */
    private $orderBy;

    /**
     * @see \App\Core\Services\Searcher\SearcherInterface::forPage()
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
        $this->search = array_get($query, 'search', '');
        $this->filters = array_get($query, 'filters', []);
        $this->orderBy = array_get($query, 'orderBy', []);
        $this->pagination = array_get($query, 'pagination', []);
    }

    /**
     * Applies this query to given searcher service and returns it.
     *
     * Technically there is no reason to return the given $searcher
     * instance, but it allows to do a nice-looking, pleasant method chaining:
     *   $query->applyTo($searcher)->get();
     *
     * @param SearcherInterface $searcher
     * @return SearcherInterface
     */
    public function applyTo(SearcherInterface $searcher): SearcherInterface
    {
        $searcher->search($this->getSearch());
        $searcher->filter($this->getFilters());

        foreach ($this->getOrderBy() as $field => $direction) {
            $searcher->orderBy($field, strtolower($direction) === 'asc');
        }

        if ($this->hasPagination()) {
            $searcher->forPage(
                $this->getPagination()['page'],
                $this->getPagination()['perPage']
            );
        }

        return $searcher;
    }

    /**
     * @return string
     */
    public function getSearch(): string
    {
        return $this->search;
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