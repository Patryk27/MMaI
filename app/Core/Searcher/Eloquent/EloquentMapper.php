<?php

namespace App\Core\Searcher\Eloquent;

use App\Core\Exceptions\Exception as CoreException;
use Illuminate\Database\Query\Expression as QueryExpression;

class EloquentMapper
{

    public const
        FIELD_TYPE_DATE = 'date',
        FIELD_TYPE_DATETIME = 'datetime',
        FIELD_TYPE_ENUM = 'enum',
        FIELD_TYPE_NUMBER = 'number',
        FIELD_TYPE_STRING = 'string';

    /**
     * @var array
     */
    private $fields;

    /**
     * @param array $fields
     */
    public function __construct(
        array $fields
    ) {
        // @todo validate structure

        $this->fields = $fields;
    }

    /**
     * @param string $fieldName
     * @return QueryExpression
     *
     * @throws CoreException
     */
    public function getColumn(string $fieldName): QueryExpression
    {
        return new QueryExpression(
            $this->get($fieldName)['column']
        );
    }

    /**
     * @param string $fieldName
     * @return string
     *
     * @throws CoreException
     */
    public function getType(string $fieldName): string
    {
        return $this->get($fieldName)['type'];
    }

    /**
     * @param string $fieldName
     * @return array
     *
     * @throws CoreException
     */
    private function get(string $fieldName): array
    {
        if (array_has($this->fields, $fieldName)) {
            return $this->fields[$fieldName];
        } else {
            throw new CoreException(
                sprintf('Field [%s] has not been mapped. Did you make a typo?', $fieldName)
            );
        }
    }

}
