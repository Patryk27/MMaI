<?php

namespace App\Routes\Implementation\Repositories;

use App\Core\Models\Morphable;
use App\Core\Repositories\InMemoryRepository;
use App\Routes\Models\Route;
use Closure;
use Illuminate\Support\Collection;

class InMemoryRoutesRepository implements RoutesRepository {

    /** @var InMemoryRepository */
    private $repository;

    public function __construct(InMemoryRepository $repository) {
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getBySubdomainAndUrl(string $subdomain, string $url): ?Route {
        return $this->repository
            ->getByMany('subdomain', $subdomain)
            ->where('url', $url)
            ->first();
    }

    /**
     * @inheritDoc
     */
    public function getLikeUrl(string $url): Collection {
        unimplemented();
    }

    /**
     * @inheritDoc
     */
    public function getPointingAt(Morphable $morphable): Collection {
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
    public function persist(Route $route): void {
        $this->repository->persist($route);
    }

    /**
     * @inheritDoc
     */
    public function delete(Route $route): void {
        $this->repository->delete($route);
    }

    /**
     * @inheritDoc
     */
    public function transaction(Closure $fn): void {
        $this->repository->transaction($fn);
    }

}
