<?php

namespace App\Attachments\Implementation\Repositories;

use App\Attachments\Models\Attachment;
use App\Core\Repositories\InMemoryRepository;
use Illuminate\Support\Collection;

class InMemoryAttachmentsRepository implements AttachmentsRepositoryInterface
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
    public function getById(int $id): ?Attachment
    {
        return $this->repository->getBy('id', $id);
    }

    /**
     * @inheritDoc
     */
    public function getByPath(string $path): ?Attachment
    {
        return $this->repository->getBy('path', $path);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    /**
     * @inheritDoc
     */
    public function getAllUnbound(): Collection
    {
        return $this->repository->getByMany('attachable_type', null);
    }

    /**
     * @inheritDoc
     */
    public function persist(Attachment $attachment): void
    {
        $this->repository->persist($attachment);
    }

    /**
     * @inheritDoc
     */
    public function delete(Attachment $attachment): void
    {
        $this->repository->delete($attachment);
    }

}
