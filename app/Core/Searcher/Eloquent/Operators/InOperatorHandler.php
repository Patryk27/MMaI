<?php

namespace App\Core\Searcher\Eloquent\Operators;

use App\Core\Exceptions\Exception as CoreException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class InOperatorHandler extends AbstractOperatorHandler implements OperatorHandler {

    /**
     * @inheritDoc
     */
    public function canHandle(string $operatorName): bool {
        return $operatorName === 'in';
    }

    /**
     * @inheritDoc
     * @throws CoreException
     */
    public function handle(
        EloquentBuilder $builder,
        string $fieldName,
        string $operatorName,
        $operatorValue
    ): void {
        if (!is_array($operatorValue)) {
            $operatorValue = (array)$operatorValue;
        }

        if (!empty($operatorValue)) {
            $builder->whereIn(
                $this->mapper->getColumn($fieldName),
                $operatorValue
            );
        }
    }

}
