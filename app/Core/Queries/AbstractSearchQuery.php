<?php

namespace App\Core\Queries;

use App\Core\Searcher\Searcher;
use Exception;
use Illuminate\Http\Request;

abstract class AbstractSearchQuery
{
    /**
     * @see Searcher::applyTextQuery()
     * @var string
     */
    private $textQuery;

    /**
     * @see Searcher::applyFilters()
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
        $this->textQuery = array_get($query, 'textQuery', '');
        $this->filters = array_get($query, 'filters', []);
        $this->orderBy = array_get($query, 'orderBy', []);
        $this->pagination = array_get($query, 'pagination', []);
    }

    /**
     * @param Request $request
     * @return $this
     * @throws Exception
     */
    public static function fromRequest(Request $request): self
    {
        if ($request->has('query')) {
            $query = json_decode($request->get('query'), true);

            if (!is_array($query)) {
                throw new Exception(sprintf(
                    'Given query is not a valid JSON.'
                ));
            }
        } else {
            $query = [];
        }

        return new static($query);
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
