<?php

namespace App\Routes\Implementation\Repositories;

use App\Core\Models\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Models\Route;
use App\Routes\Models\Route as Routes;
use Illuminate\Support\Collection;

class InMemoryRoutesRepository implements RoutesRepository
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
    public function getBySubdomainAndUrl(string $subdomain, string $url): ?Routes
    {
        return $this->repository
            ->getByMany('subdomain', $subdomain)
            ->where('url', $url)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getLikeUrl(string $url): Collection
    {
        unimplemented();
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
