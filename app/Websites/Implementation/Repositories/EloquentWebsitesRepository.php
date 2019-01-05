<?php

namespace App\Websites\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Websites\Models\Website;
use Illuminate\Support\Collection;

class EloquentWebsitesRepository implements WebsitesRepository {
    /** @var EloquentRepository */
    private $repository;

    public function __construct(Website $website) {
        $this->repository = new EloquentRepository($website);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Website {
        return $this->repository->getByid($id);
    }

    /**
     * @inheritDoc
     */
    public function getBySlug(string $slug): ?Website {
        return $this->repository->getBy('slug', $slug);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection {
        return $this->repository->getAll();
    }
}
