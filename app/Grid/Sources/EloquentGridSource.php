<?php

namespace App\Grid\Sources;

use App\Grid\Query\GridQueryFilter;
use App\Grid\Query\GridQueryPagination;
use App\Grid\Query\GridQuerySorting;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;
use LogicException;

abstract class EloquentGridSource implements GridSource {

    private const ELOQUENT_OPERATORS = [
        GridQueryFilter::OPERATOR_EQ => '==',
        GridQueryFilter::OPERATOR_NEQ => '!=',
        GridQueryFilter::OPERATOR_GT => '>',
        GridQueryFilter::OPERATOR_GTE => '>=',
        GridQueryFilter::OPERATOR_LT => '<',
        GridQueryFilter::OPERATOR_LTE => '<=',

        GridQueryFilter::OPERATOR_LIKE => 'like',
        GridQueryFilter::OPERATOR_NOT_LIKE => 'not like',
    ];

    /** @var EloquentBuilder */
    private $query;

    public function __construct() {
        $this->query = static::getNewQuery();
    }

    /**
     * {@inheritdoc}
     */
    public function applyFilter(GridQueryFilter $filter): void {
        $operator = $filter->getOperator();
        $field = $this->translateField($filter->getField());
        $value = $filter->getValue();

        if (array_key_exists($operator, self::ELOQUENT_OPERATORS)) {
            $this->query->where($field, self::ELOQUENT_OPERATORS[$operator], $value);
            return;
        }

        switch ($operator) {
            case GridQueryFilter::OPERATOR_IN_SET:
                $this->query->whereIn($field, $value);
                return;

            case GridQueryFilter::OPERATOR_NOT_IN_SET:
                $this->query->whereNotIn($field, $value);
                return;
        }

        throw new LogicException(sprintf('Unknown operator: [%s]', $operator));
    }

    /**
     * {@inheritdoc}
     */
    public function applySorting(GridQuerySorting $sorting): void {
        $this->query->orderBy(
            $this->translateField($sorting->getField()),
            $sorting->getDirection()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function applyPagination(GridQueryPagination $pagination): void {
        $this->query->forPage(
            $pagination->getPage(),
            $pagination->getPerPage()
        );
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int {
        return $this->query->count();
    }

    /**
     * {@inheritdoc}
     */
    public function get(): Collection {
        return $this->query->get();
    }

    /**
     * @param string $field
     * @return string
     */
    abstract protected function translateField(string $field): string;

    /**
     * @return EloquentBuilder
     */
    abstract protected static function getNewQuery(): EloquentBuilder;

}
