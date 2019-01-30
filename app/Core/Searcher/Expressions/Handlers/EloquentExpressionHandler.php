<?php

namespace App\Core\Searcher\Expressions\Handlers;

use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Core\Searcher\Expressions\ExpressionHandler;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression as QueryExpression;

class EloquentExpressionHandler implements ExpressionHandler {

    /** @var EloquentBuilder */
    private $builder;

    /** @var string */
    private $fieldType;

    /** @var QueryExpression */
    private $fieldColumn;

    public function __construct(
        EloquentBuilder $builder,
        string $fieldType,
        QueryExpression $fieldColumn
    ) {
        $this->builder = $builder;
        $this->fieldType = $fieldType;
        $this->fieldColumn = $fieldColumn;
    }

    /**
     * @inheritDoc
     */
    public function between($min, $max): void {
        if ($this->fieldType === EloquentMapper::FIELD_TYPE_NUMBER) {
            $min = (float)$min;
            $max = (float)$max;
        }

        $this->builder->whereBetween($this->fieldColumn, [$min, $max]);
    }

    /**
     * @inheritDoc
     */
    public function regex($regex): void {
        $this->builder->whereRaw(
            sprintf('%s regexp ?', $this->fieldColumn),
            [$regex]
        );
    }

}
