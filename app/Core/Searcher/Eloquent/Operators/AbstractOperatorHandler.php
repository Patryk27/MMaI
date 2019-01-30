<?php

namespace App\Core\Searcher\Eloquent\Operators;

use App\Core\Searcher\Eloquent\EloquentMapper;

abstract class AbstractOperatorHandler implements OperatorHandler {

    /** @var EloquentMapper */
    protected $mapper;

    /**
     * @param EloquentMapper $mapper
     */
    public function __construct(EloquentMapper $mapper) {
        $this->mapper = $mapper;
    }

}
