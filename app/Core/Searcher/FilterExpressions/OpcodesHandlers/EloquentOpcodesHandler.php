<?php

namespace App\Core\Searcher\FilterExpressions\OpcodesHandlers;

use App\Core\Searcher\FilterExpressions\OpcodesHandler;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression as QueryExpression;

class EloquentOpcodesHandler implements OpcodesHandler
{

    /**
     * @var EloquentBuilder
     */
    private $builder;

    /**
     * @var QueryExpression
     */
    private $column;

    /**
     * @param EloquentBuilder $builder
     * @param QueryExpression $column
     */
    public function __construct(
        EloquentBuilder $builder,
        QueryExpression $column
    ) {
        $this->builder = $builder;
        $this->column = $column;
    }

    /**
     * @inheritDoc
     */
    public function between($min, $max): void
    {
        $this->builder->whereBetween($this->column, [$min, $max]);
    }

    /**
     * @inheritDoc
     */
    public function regex($regex): void
    {
        $this->builder->whereRaw(
            sprintf('%s regexp ?', $this->column),
            [$regex]
        );
    }

}
