<?php

namespace App\Core\Searcher\Eloquent;

use App\Core\Exceptions\Exception as CoreException;
use App\Core\Searcher\Eloquent\Operators\ExpressionOperatorHandler;
use App\Core\Searcher\Eloquent\Operators\InOperatorHandler;
use App\Core\Searcher\Eloquent\Operators\OperatorHandler;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class EloquentFilterer
{
    /** @var OperatorHandler[] */
    private $operatorHandlers;

    public function __construct(EloquentMapper $mapper)
    {
        $this->operatorHandlers = [
            new ExpressionOperatorHandler($mapper),
            new InOperatorHandler($mapper),
        ];
    }

    /**
     * @param EloquentBuilder $builder
     * @param array $filters
     * @return void
     * @throws CoreException
     */
    public function applyFilters(EloquentBuilder $builder, array $filters): void
    {
        foreach ($filters as $fieldName => $filter) {
            if (is_array($filter)) {
                $operatorName = array_get($filter, 'operator');
                $operatorValue = array_get($filter, 'value');
            } else {
                // @todo provide comment
                $operatorName = 'expression';
                $operatorValue = $filter;
            }

            $this->applyFilter($builder, $fieldName, $operatorName, $operatorValue);
        }
    }

    /**
     * @param EloquentBuilder $builder
     * @param string $fieldName
     * @param string $operatorName
     * @param mixed $operatorValue
     * @return void
     * @throws CoreException
     */
    private function applyFilter(
        EloquentBuilder $builder,
        string $fieldName,
        string $operatorName,
        $operatorValue
    ): void {
        foreach ($this->operatorHandlers as $operatorHandler) {
            if ($operatorHandler->canHandle($operatorName)) {
                $operatorHandler->handle($builder, $fieldName, $operatorName, $operatorValue);
                return;
            }
        }

        throw new CoreException(sprintf(
            'Unknown operator: [%s].', $operatorName
        ));
    }
}
