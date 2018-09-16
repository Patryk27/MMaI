<?php

namespace App\Core\Repositories;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use LogicException;

/**
 * This class provides a few basic implementations which can be used to
 * facilitate building in-memory repositories.
 *
 * E.g. @see \App\Routes\Implementation\Repositories\InMemoryRoutesRepository.
 */
final class InMemoryRepository
{

    /**
     * List of all the items present in this repository.
     *
     * @var Collection|Model[]
     */
    private $items;

    /**
     * Auto-incrementing id used for counting models - works in the same fashion
     * as e.g. the MySQL's "auto increment" column.
     *
     * @var int
     */
    private $incrementingId = 1000;

    /**
     * Constructs an in-memory repository pre-feeded with given items.
     *
     * @param array $items
     */
    public function __construct(
        array $items = []
    ) {
        $this->items = new Collection();

        foreach ($items as $item) {
            $this->insert($item);
        }
    }

    /**
     * Returns a single model matching given criteria.
     * Returns `null` if no such model exists.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return mixed|null
     */
    public function getBy(string $attributeName, $attributeValue)
    {
        $models = $this->getByMany($attributeName, $attributeValue);

        if ($models->isEmpty()) {
            return null;
        }

        return clone $models->first();
    }

    /**
     * Returns a list of models matching given criteria.
     *
     * @param string $attributeName
     * @param mixed $attributeValue
     * @return Collection|Model[]
     */
    public function getByMany(string $attributeName, $attributeValue): Collection
    {
        return $this->items
            ->where($attributeName, $attributeValue)
            ->map(function (Model $model): Model {
                return clone $model;
            });
    }

    /**
     * Returns all the models this repository contains.
     *
     * @return Collection|Model[]
     */
    public function getAll(): Collection
    {
        return $this->items->map(function (Model $model): Model {
            return clone $model;
        });
    }

    /**
     * Creates / updates given model in the repository.
     *
     * @param Model $model
     * @return void
     */
    public function persist(Model $model): void
    {
        if ($model->exists) {
            $this->update($model);
        } else {
            $this->insert($model);
        }
    }

    /**
     * Deletes given model from the repository.
     *
     * @param Model $model
     * @return void
     */
    public function delete(Model $model): void
    {
        $id = $model->getAttribute('id');

        if (!$this->items->has($id)) {
            throw new LogicException(
                'Tried to remove model not present in the in-memory repository.'
            );
        }

        $this->items->forget($id);
    }

    /**
     * @param Model $model
     * @return void
     */
    private function insert(Model $model): void
    {
        if ($this->items->has($this->incrementingId)) {
            throw new LogicException(
                sprintf('In-memory repository already contains model [id=%d].', $this->incrementingId)
            );
        }

        // Fill-in the "id" attribute
        $model->setAttribute('id', $this->incrementingId);

        // Fill-in the "created at" attribute
        if ($model->getAttribute('created_at') === null) {
            $model->setAttribute('created_at', Carbon::now());
        }

        // Fill-in the "updated at" attribute
        if ($model->getAttribute('updated_at') === null) {
            $model->setAttribute('updated_at', Carbon::now());
        }

        // Mark model as "existing"
        $model->exists = true;

        // Persist it
        $this->items[$this->incrementingId] = clone $model;
        $this->incrementingId += 1;
    }

    /**
     * @param Model $model
     * @return void
     */
    private function update(Model $model): void
    {
        $id = $model->getAttribute('id');

        if (!$this->items->has($id)) {
            throw new LogicException(
                sprintf('Tried to update a model not present in the in-memory repository (id=%d).', $id)
            );
        }

        // Fill-in the "updated at" attribute
        $model->setAttribute('updated_at', Carbon::now());

        // Persist the model
        $this->items[$id] = clone $model;
    }

}
