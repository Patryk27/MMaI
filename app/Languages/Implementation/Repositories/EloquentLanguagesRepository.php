<?php

namespace App\Languages\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Languages\Models\Language;
use Illuminate\Support\Collection;

class EloquentLanguagesRepository implements LanguagesRepository {

    /** @var EloquentRepository */
    private $repository;

    public function __construct(Language $language) {
        $this->repository = new EloquentRepository($language);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Language {
        return $this->repository->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function getBySlug(string $slug): ?Language {
        return $this->repository->getBy('slug', $slug);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection {
        return $this->repository->getAll();
    }

}
