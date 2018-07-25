<?php

namespace App\Core\Services\Searcher;

use App\Exceptions\Exception as AppException;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use LogicException;

/**
 * This is a generic searcher used to facilitate creating searcher services by
 * providing basic implementations for methods required to create such service.
 *
 * Contrary to @see AbstractSearcher, you should *not* inherit from this one -
 * use composition.
 */
class GenericSearcher
{

    const
        FILTER_OP_EQUAL = 'equal',
        FILTER_OP_NOT_EQUAL = 'not equal',
        FILTER_OP_LIKE = 'like';

    /**
     * @var EloquentBuilder
     */
    private $builder;

    /**
     * Maps field name (e.g. "language_id") into database column (e.g.
     * "languages.id").
     *
     * @var string[]
     */
    private $fieldsMap;

    /**
     * @param Model $model
     * @param array $fieldsMap
     */
    public function __construct(
        Model $model,
        array $fieldsMap
    ) {
        $this->builder = $model->newQuery();
        $this->fieldsMap = $fieldsMap;
    }

    /**
     * Provides a basic implementation for the @see SearcherInterface::search()
     * method.
     *
     * It builds a query combining given fields using "OR" and "LIKE" operators.
     *
     * Example:
     *   search('something', ['firstField', 'secondField'])
     *
     * Example result:
     *   $builder
     *     ->where(fieldToColumn('firstField'), 'like', '%something%')
     *     ->orWhere(fieldToColumn('secondField'), 'like', '%something%');
     *
     *
     * @param string $search
     * @param array $fieldsToInclude
     * @return void
     */
    public function search(string $search, array $fieldsToInclude): void
    {
        $search = trim($search);

        if (strlen($search) === 0) {
            return;
        }

        $this->builder->where(function (EloquentBuilder $builder) use ($search, $fieldsToInclude) {
            foreach ($fieldsToInclude as $field) {
                $column = $this->fieldToColumn($field);

                $builder->orWhere($column, 'like', '%' . $search . '%');
            }
        });
    }

    /**
     * Provides a basic implementation for the @see SearcherInterface::filter()
     * method.
     *
     * It allows to implement a very simple filter for given fields.
     *
     * Example:
     *   filter($someValues, [
     *     'firstField' => GenericSearcher::FILTER_OP_EQUAL,
     *     'secondField' => GenericSearcher::FILTER_OP_EQUAL,
     *   ]);
     *
     * @param array $fields
     * @param array $fieldFilters
     * @return void
     *
     * @throws AppException
     */
    public function filter(array $fields, array $fieldFilters): void
    {
        foreach ($fields as $field => $fieldValue) {
            if (!array_has($fieldFilters, $field)) {
                throw new AppException(
                    sprintf('Field [%s] it not filterable', $field)
                );
            }

            $column = $this->fieldToColumn($field);

            switch ($fieldFilters[$field]) {
                case self::FILTER_OP_EQUAL:
                    $this->builder->where($column, $fieldValue);
                    break;

                case self::FILTER_OP_NOT_EQUAL:
                    $this->builder->where($column, '!=', $fieldValue);
                    break;

                case self::FILTER_OP_LIKE;
                    $this->builder->where($column, 'like', '%' . $fieldValue . '%');
                    break;

                default:
                    throw new LogicException(
                        sprintf('Unknown field filter: [%s].', $fieldFilters[$field])
                    );
            }
        }
    }

    /**
     * @see SearcherInterface::orderBy()
     *
     * @param string $field
     * @param bool $ascending
     * @return void
     *
     * @throws AppException
     */
    public function orderBy(string $field, bool $ascending): void
    {
        $this->builder->orderBy(
            $this->fieldToColumn($field),
            $ascending ? 'asc' : 'desc'
        );
    }

    /**
     * @see SearcherInterface::forPage()
     *
     * @param int $page
     * @param int $perPage
     * @return void
     */
    public function forPage(int $page, int $perPage): void
    {
        $this->builder->forPage($page, $perPage);
    }

    /**
     * @see SearcherInterface::get()
     *
     * @return Collection
     */
    public function get(): Collection
    {
        return $this->builder->get();
    }

    /**
     * @see SearcherInterface::getCount()
     *
     * @return int
     */
    public function getCount(): int
    {
        return $this->builder->count();
    }

    /**
     * Returns database column which corresponds to given field name.
     * Throws an exception if given field does not exist.
     *
     * E.g.:
     *   fieldToColumn('language_id') -> 'languages.id'
     *
     * @param string $field
     * @return string
     *
     * @throws AppException
     */
    public function fieldToColumn(string $field): string
    {
        if (!array_has($this->fieldsMap, $field)) {
            throw new AppException(
                sprintf('Field [%s] does not exist.', $field)
            );
        }

        return $this->fieldsMap[$field];
    }

    /**
     * @return EloquentBuilder
     */
    public function getBuilder(): EloquentBuilder
    {
        return $this->builder;
    }

}