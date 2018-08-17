<?php

namespace App\Core\Repositories;

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
     * List of all the items this repository contains.
     *
     * @var Collection|Model[]
     */
    private $items;

    /**
     * Number which the next inserted item is going to be assigned.
     * It is automatically incremented then.
     *
     * @var int
     */
    private $incrementingId = 1000;

    /**
     * Constructs an in-memory repository.
     *
     * Given list of items is automatically inserted into the repository, in
     * form of fixtures.
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
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->items->map(function (Model $model): Model {
            return clone $model;
        });
    }

    /**
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
     * @param Model $model
     * @return void
     */
    public function delete(Model $model): void
    {
        $modelId = $model->getAttribute('id');

        // Make sure we have that model in the collection
        if (!$this->items->has($modelId)) {
            throw new LogicException(
                'Tried to remove model not present in the in-memory repository.'
            );
        }

        $this->items->forget($modelId);
    }

    /**
     * @param Model $model
     * @return void
     */
    private function insert(Model $model): void
    {
        $model->setAttribute('id', $this->incrementingId);

        // It may be the case that, for some weird reason, our target slot is
        // already taken - this is an error and so we are throwing a logic
        // exception in such cases.
        if ($this->items->has($this->incrementingId)) {
            throw new LogicException(
                sprintf('In-memory repository already contains model [id=%d].', $this->incrementingId)
            );
        }

        // Save model into the collection
        $this->items[$this->incrementingId] = clone $model;

        // Flag model as "existing"
        $model->exists = true;

        ++$this->incrementingId;
    }

    /**
     * @param Model $model
     * @return void
     */
    private function update(Model $model): void
    {
        $modelId = $model->getAttribute('id');

        // Make sure we have that model in the collection
        if (!$this->items->has($modelId)) {
            throw new LogicException(
                'Tried to update model not present in the in-memory repository.'
            );
        }

        $this->items[$modelId] = $model;
    }

}