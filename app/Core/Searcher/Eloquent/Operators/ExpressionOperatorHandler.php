<?php

namespace App\Core\Searcher\Eloquent\Operators;

use App\Core\Exceptions\Exception as CoreException;
use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Core\Searcher\Expressions\ExpressionException;
use App\Core\Searcher\Expressions\ExpressionParser;
use App\Core\Searcher\Expressions\ExpressionProcessor;
use App\Core\Searcher\Expressions\Handlers\EloquentExpressionHandler;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Query\Expression as QueryExpression;
use LogicException;

class ExpressionOperatorHandler extends AbstractOperatorHandler implements OperatorHandler
{
    /**
     * @inheritDoc
     */
    public function canHandle(string $operatorName): bool
    {
        return $operatorName === 'expression';
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
        // Get field's column & type
        $fieldColumn = $this->mapper->getColumn($fieldName);
        $fieldType = $this->mapper->getType($fieldName);

        // Sanitize the input
        $operatorValue = trim($operatorValue);

        if (strlen($operatorValue) === 0) {
            return;
        }

        // An expression must start with ":", otherwise it's just a plain string
        if ($operatorValue[0] === ':') {
            $this->handleExpression($builder, $fieldType, $fieldColumn, $operatorValue);
        } else {
            $this->handleRawString($builder, $fieldType, $fieldColumn, $operatorValue);
        }
    }

    /**
     * @param EloquentBuilder $builder
     * @param string $fieldType
     * @param QueryExpression $fieldColumn
     * @param string $operatorValue
     * @return void
     * @throws ExpressionException
     */
    private function handleExpression(
        EloquentBuilder $builder,
        string $fieldType,
        QueryExpression $fieldColumn,
        string $operatorValue
    ): void {
        $expressionProcessor = new ExpressionProcessor(
            new ExpressionParser(),
            new EloquentExpressionHandler($builder, $fieldType, $fieldColumn)
        );

        $expressionProcessor->process($operatorValue);
    }

    /**
     * @param EloquentBuilder $builder
     * @param string $fieldType
     * @param QueryExpression $fieldColumn
     * @param string $operatorValue
     * @return void
     */
    private function handleRawString(
        EloquentBuilder $builder,
        string $fieldType,
        QueryExpression $fieldColumn,
        string $operatorValue
    ): void {
        switch ($fieldType) {
            // Dates, enumerations and numbers are matched in an "equals to"
            // fashion
            case EloquentMapper::FIELD_TYPE_DATE:
            case EloquentMapper::FIELD_TYPE_DATETIME:
            case EloquentMapper::FIELD_TYPE_ENUM:
            case EloquentMapper::FIELD_TYPE_NUMBER:
                $builder->where($fieldColumn, $operatorValue);

                break;

            // Strings are matched in a "like" fashion
            case EloquentMapper::FIELD_TYPE_STRING:
                $builder->whereRaw(
                    sprintf('%s like ?', $fieldColumn),
                    ['%' . $operatorValue . '%']
                );

                break;

            default:
                throw new LogicException(sprintf(
                    'Unexpected field type: [%s].', $fieldType
                ));
        }
    }
}
