<?php

namespace App\Routes\Internal\Repositories;

use App\Core\Models\Interfaces\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Models\Route;
use App\Routes\Models\Route as Routes;
use Illuminate\Support\Collection;

class RoutesInMemoryRepository implements RoutesRepositoryInterface
{

    /**
     * @var InMemoryRepository
     */
    private $repository;

    /**
     * @param InMemoryRepository $repository
     */
    public function __construct(
        InMemoryRepository $repository
    ) {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getByUrl(string $url): ?Routes
    {
        return $this->repository->getBy('url', $url);
    }

    /**
     * @inheritDoc
     */
    public function getPointingAt(Morphable $morphable): Collection
    {
        return $this->repository
            ->getAll()
            ->filter(function (Route $route) use ($morphable): bool {
                return $route->model_type === $morphable::getMorphableType()
                    && $route->model_id === $morphable->getMorphableId();
            });
    }

    /**
     * @inheritDoc
     */
    public function persist(Routes $route): void
    {
        $this->repository->persist($route);
    }

    /**
     * @inheritDoc
     */
    public function delete(Routes $route): void
    {
        $this->repository->delete($route);
    }

}