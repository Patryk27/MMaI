<?php

namespace App\Grid\Query;

use App\Core\ValueObjects\HasInitializationConstructor;

final class GridQueryFilter {

    public const
        OPERATOR_EQ = 'eq',
        OPERATOR_NEQ = 'neq',
        OPERATOR_GT = 'gt',
        OPERATOR_GTE = 'gte',
        OPERATOR_LT = 'lt',
        OPERATOR_LTE = 'lte';

    public const
        OPERATOR_LIKE = 'like',
        OPERATOR_NOT_LIKE = 'not-like';

    public const
        OPERATOR_IN_SET = 'in-set',
        OPERATOR_NOT_IN_SET = 'not-in-set';

    use HasInitializationConstructor;

    /** @var string */
    private $field;

    /** @var string */
    private $operator;

    /** @var mixed */
    private $value;

    /**
     * @return string
     */
    public function getField(): string {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getOperator(): string {
        return $this->operator;
    }

    /**
     * @return mixed
     */
    public function getValue() {
        return $this->value;
    }

}
