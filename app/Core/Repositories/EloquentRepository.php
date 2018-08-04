<?php

namespace App\Core\Repositories;

use App\Core\Exceptions\RepositoryException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Throwable;

/**
 * This class provides a few basic implementations which can be used to
 * facilitate building Eloquent-based repositories.
 *
 * E.g. @see \App\Routes\Implementation\Repositories\EloquentRoutesRepository.
 */
final class EloquentRepository
{

    /**
     * @var Model
     */
    private $model;

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
     */
    public function getById(int $id)
    {
        return $this->getBy('id', $id);
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
     * @see getByMany()
     */
    public function getBy(string $attributeName, $attributeValue)
    {
        return $this->model->where($attributeName, $attributeValue)->first();
    }

    /**
     * Returns all models having given attribute.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return Collection|Model[]
     */
    public function getByMany(string $attributeName, $attributeValue): Collection
    {
        return $this->model->where($attributeName, $attributeValue)->get();
    }

    /**
     * Returns all models.
     *
     * @return Collection|Model[]
     */
    public function getAll(): Collection
    {
        return $this->model->all();
    }

    /**
     * Throws an exception if given parameter is not a valid model for this
     * repository.
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
                sprintf('model is not an instance of [%s].', get_class($this->model))
            );
        }
    }

}