<?php

namespace App\Core\Queries;

use App\Core\Searcher\Searcher;

abstract class AbstractSearchQuery
{
    /**
     * @see Searcher::search()
     * @var string
     */
    private $query;

    /**
     * @see Searcher::filter()
     * @var array
     */
    private $filters;

    /**
     * @see Searcher::orderBy()
     * @var array
     */
    private $orderBy;

    /**
     * @see Searcher::paginate()
     * @var array
     */
    private $pagination;

    public function __construct(array $query)
    {
        $this->query = array_get($query, 'query', '');
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
        $searcher->search(
            $this->getQuery()
        );

        $searcher->filter(
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
    public function getQuery(): string
    {
        return $this->query;
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
