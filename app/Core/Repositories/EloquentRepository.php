<?php

namespace App\Core\Repositories;

use App\Core\Exceptions\RepositoryException;
use App\Core\Models\Model;
use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Collection;
use Throwable;

/**
 * This class provides a few basic implementations which can be used to
 * facilitate building Eloquent-based repositories.
 *
 * E.g. @see \App\Routes\Implementation\Repositories\EloquentRoutesRepository.
 */
final class EloquentRepository {

    /** @var Model */
    private $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    /**
     * @return EloquentBuilder
     */
    public function newQuery(): EloquentBuilder {
        return $this->model->newQuery();
    }

    /**
     * Returns a single model with given primary key or `null` if no such model
     * exists.
     *
     * @param int $id
     * @return mixed|null
     */
    public function getById(int $id) {
        return $this->getBy('id', $id);
    }

    /**
     * Creates or updates given model in the database.
     *
     * @param Model $model
     * @return void
     * @throws Throwable
     */
    public function persist(Model $model): void {
        $this->assertModelHasCorrectType($model);
        $model->saveOrThrow();
    }

    /**
     * Deletes given model from the database.
     *
     * @param Model $model
     * @return void
     * @throws Throwable
     */
    public function delete(Model $model): void {
        $this->assertModelHasCorrectType($model);
        $model->delete();
    }

    /**
     * Returns a single model with specified attribute's value.
     * Returns `null` if no such model exists.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return mixed|null
     */
    public function getBy(string $attributeName, $attributeValue) {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->model->where($attributeName, $attributeValue)->first();
    }

    /**
     * Returns all models having given attribute.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return Collection|Model[]
     */
    public function getByMany(string $attributeName, $attributeValue): Collection {
        /** @noinspection PhpUndefinedMethodInspection */
        return $this->model->where($attributeName, $attributeValue)->get();
    }

    /**
     * Returns all the models.
     *
     * @return Collection|Model[]
     */
    public function getAll(): Collection {
        return $this->model->all();
    }

    /**
     * Throws an exception if given parameter is not a valid model for this
     * repository.
     *
     * @param mixed $model
     * @return void
     * @throws RepositoryException
     */
    private function assertModelHasCorrectType($model): void {
        if (!$model instanceof $this->model) {
            throw RepositoryException::makeModelIsInvalid(sprintf(
                'model is not an instance of [%s].', get_class($this->model)
            ));
        }
    }

    /**
     * Executes specified action in a database transaction.
     *
     * @param Closure $fn
     * @throws Throwable
     */
    public function transaction(Closure $fn): void {
        $this->model->getConnection()->transaction($fn);
    }

}
