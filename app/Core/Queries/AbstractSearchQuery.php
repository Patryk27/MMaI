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
    private $filter;

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
        $this->filter = array_get($query, 'filter', []);
        $this->orderBy = array_get($query, 'orderBy', []);
        $this->pagination = array_get($query, 'pagination', []);
    }

    /**
     * Applies this query to given searcher service.
     *
     * @param SearcherInterface $searcher
     * @return void
     */
    public function applyTo(SearcherInterface $searcher): void
    {
        $searcher->search($this->getSearch());
        $searcher->filter($this->getFilter());

        foreach ($this->getOrderBy() as $field => $direction) {
            $searcher->orderBy($field, $direction === 'asc');
        }

        if ($this->hasPagination()) {
            $searcher->forPage(
                $this->getPagination()['page'],
                $this->getPagination()['perPage']
            );
        }
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
    public function getFilter(): array
    {
        return $this->filter;
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