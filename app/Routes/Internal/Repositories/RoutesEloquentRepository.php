<?php

namespace App\Routes\Internal\Repositories;

use App\Core\Models\Interfaces\Morphable;
use App\Core\Repositories\EloquentRepository;
use App\Routes\Models\Route;
use Illuminate\Support\Collection;
use Throwable;

class RoutesEloquentRepository implements RoutesRepositoryInterface
{

    /**
     * @var EloquentRepository
     */
    private $repository;

    /**
     * @param Route $route
     */
    public function __construct(
        Route $route
    ) {
        $this->repository = new EloquentRepository($route);
    }

    /**
     * @inheritDoc
     */
    public function getByUrl(string $url): ?Route
    {
        return $this->repository->getBy('url', $url);
    }

    /**
     * @inheritDoc
     */
    public function getPointingAt(Morphable $morphable): Collection
    {
        $stmt = $this->repository->getModel()->newQuery();
        $stmt->where([
            'model_id' => $morphable->getMorphableId(),
            'model_type' => $morphable::getMorphableType(),
        ]);

        return $stmt->get();
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function persist(Route $route): void
    {
        $this->repository->persist($route);
    }

    /**
     * @inheritDoc
     *
     * @throws Throwable
     */
    public function delete(Route $route): void
    {
        $this->repository->delete($route);
    }

}