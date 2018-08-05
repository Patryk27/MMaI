<?php

namespace App\Pages\Implementation\Repositories;

use App\Core\Repositories\InMemoryRepository;
use App\Pages\Models\Page;

class InMemoryPagesRepository implements PagesRepositoryInterface
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
    public function getById(int $id): ?Page
    {
        return $this->repository->getBy('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function persist(Page $page): void
    {
        $this->repository->persist($page);

        foreach ($page->pageVariants as $pageVariant) {
            $pageVariant->page()->associate($page);
        }
    }

}