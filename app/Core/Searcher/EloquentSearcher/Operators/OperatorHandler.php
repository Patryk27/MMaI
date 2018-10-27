<?php

namespace App\Core\Searcher\EloquentSearcher\Operators;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

interface OperatorHandler
{

    /**
     * @param string $operatorName
     * @return bool
     */
    public function canHandle(string $operatorName): bool;

    /**
     * @param EloquentBuilder $builder
     * @param string $fieldName
     * @param string $operatorName
     * @param mixed $operatorValue
     * @return void
     */
    public function handle(
        EloquentBuilder $builder,
        string $fieldName,
        string $operatorName,
        $operatorValue
    ): void;

}
