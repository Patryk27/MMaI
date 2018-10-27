<?php

namespace App\Core\Searcher\EloquentSearcher\Operators;

use App\Core\Searcher\EloquentSearcher\EloquentMapper;

abstract class AbstractOperatorHandler implements OperatorHandler
{

    /**
     * @var EloquentMapper
     */
    protected $mapper;

    /**
     * @param EloquentMapper $mapper
     */
    public function __construct(
        EloquentMapper $mapper
    ) {
        $this->mapper = $mapper;
    }

}
