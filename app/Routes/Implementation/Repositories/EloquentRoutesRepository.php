<?php

namespace App\Routes\Implementation\Repositories;

use App\Core\Models\Morphable;
use App\Core\Repositories\EloquentRepository;
use App\Routes\Models\Route;
use Illuminate\Support\Collection;
use Throwable;

class EloquentRoutesRepository implements RoutesRepository
{
    /** @var EloquentRepository */
    private $repository;

    public function __construct(Route $route)
    {
        $this->repository = new EloquentRepository($route);
    }

    /**
     * @inheritDoc
     */
    public function getBySubdomainAndUrl(string $subdomain, string $url): ?Route
    {
        return $this->repository
            ->newQuery()
            ->where([
                'subdomain' => $subdomain,
                'url' => $url,
            ])
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getLikeUrl(string $url): Collection
    {
        return $this->repository
            ->newQuery()
            ->where('url', 'like', $url)
            ->get();
    }

    /**
     * @inheritDoc
     */
    public function getPointingAt(Morphable $morphable): Collection
    {
        return $this->repository
            ->newQuery()
            ->where([
                'model_id' => $morphable->getMorphableId(),
                'model_type' => $morphable::getMorphableType(),
            ])
            ->get();
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function persist(Route $route): void
    {
        $this->repository->persist($route);
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function delete(Route $route): void
    {
        $this->repository->delete($route);
    }
}
