<?php

namespace App\Core\Searcher;

use App\Core\Exceptions\Exception as CoreException;
use App\Core\Searcher\Eloquent\EloquentFilterer;
use App\Core\Searcher\Eloquent\EloquentMapper;
use App\Core\Searcher\Eloquent\EloquentTextQuerier;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class AbstractEloquentSearcher implements Searcher
{

    /**
     * @var EloquentBuilder
     */
    protected $builder;

    /**
     * @var EloquentMapper
     */
    protected $mapper;

    /**
     * @var EloquentTextQuerier
     */
    protected $textQuerier;

    /**
     * @var EloquentFilterer
     */
    protected $filterer;

    /**
     * @param Model $model
     * @param array $fields
     */
    public function __construct(
        Model $model,
        array $fields
    ) {
        $this->builder = $model->newQuery();
        $this->mapper = new EloquentMapper($fields);
        $this->textQuerier = new EloquentTextQuerier($this->mapper);
        $this->filterer = new EloquentFilterer($this->mapper);
    }

    /**
     * @inheritdoc
     */
    public function applyTextQuery(string $query): void
    {
        $this->textQuerier->applyTextQuery($query);
    }

    /**
     * @inheritdoc
     *
     * @throws CoreException
     */
    public function applyFilters(array $fields): void
    {
        $this->filterer->applyFilters($this->builder, $fields);
    }

    /**
     * @inheritdoc
     *
     * @throws CoreException
     */
    public function orderBy(string $fieldName, bool $ascending): void
    {
        $this->builder->orderBy(
            $this->mapper->getColumn($fieldName),
            $ascending ? 'asc' : 'desc'
        );
    }

    /**
     * @inheritdoc
     */
    public function paginate(int $page, int $perPage): void
    {
        $this->builder->forPage($page, $perPage);
    }

    /**
     * @inheritdoc
     */
    public function get(): Collection
    {
        return $this->builder->get();
    }

    /**
     * @inheritdoc
     */
    public function count(): int
    {
        return $this->builder->count();
    }

}
