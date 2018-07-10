<?php

namespace App\Repositories;

use App\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\Eloquent\Model;
use Throwable;

/**
 * This is a generic repository used to facilitate creating repositories.
 * It should not be inherited from - use composition.
 */
class GenericRepository
{

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param Model $model
     */
    public function __construct(
        Model $model
    ) {
        $this->model = $model;
    }

    /**
     * Returns a single model with given primary key or `null` if no such model
     * exists.
     *
     * @param int $id
     * @return mixed|null
     *
     * @see getByIdOrFail()
     */
    public function getById(int $id)
    {
        return $this->getBy('id', $id);
    }

    /**
     * Returns a single model with given primary key or throws an exception if
     * no such model exists.
     *
     * @param int $id
     * @return mixed
     *
     * @throws RepositoryException
     *
     * @see getById()
     */
    public function getByIdOrFail(int $id)
    {
        return $this->getByOrFail('id', $id);
    }

    /**
     * Creates or updates given model in the database.
     *
     * @param Model $model
     * @return void
     *
     * @throws Throwable
     */
    public function persist(Model $model): void
    {
        $this->assertModelIsOfCorrectType($model);
        $model->saveOrFail();
    }

    /**
     * Deletes given model from the database.
     *
     * @param Model $model
     * @return void
     *
     * @throws Throwable
     */
    public function delete(Model $model): void
    {
        $this->assertModelIsOfCorrectType($model);
        $model->delete();
    }

    /**
     * Returns a single model with specified attribute's value.
     * Returns `null` if no such model exists.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return mixed|null
     *
     * @see getByOrFail()
     * @see getByMany()
     */
    public function getBy(string $attributeName, $attributeValue)
    {
        return $this->model->where($attributeName, $attributeValue)->first();
    }

    /**
     * Returns a single model with specified attribute's value.
     * Throws an exception if no such model exists.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return mixed
     *
     * @throws RepositoryException
     *
     * @see getBy()
     * @see getByMany()
     */
    public function getByOrFail(string $attributeName, $attributeValue)
    {
        $model = $this->getBy($attributeName, $attributeValue);

        if (is_null($model)) {
            throw RepositoryException::makeModelNotFound(
                get_class($this->model), $attributeName, $attributeValue
            );
        }

        return $model;
    }

    /**
     * Returns all models having given attribute.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return EloquentCollection|Model[]
     */
    public function getByMany(string $attributeName, $attributeValue): EloquentCollection
    {
        return $this->model->where($attributeName, $attributeValue)->get();
    }

    /**
     * Returns all models.
     *
     * @return EloquentCollection|Model[]
     */
    public function getAll(): EloquentCollection
    {
        return $this->model->all();
    }

    /**
     * Throws an exception if given parameter is not a valid model for this repository.
     *
     * @param mixed $model
     * @return void
     *
     * @throws RepositoryException
     */
    private function assertModelIsOfCorrectType($model): void
    {
        if (!$model instanceof $this->model) {
            throw RepositoryException::makeModelIsInvalid(
                sprintf(
                    'model is not an instance of [%s].',
                    get_class($this->model)
                )
            );
        }
    }

}