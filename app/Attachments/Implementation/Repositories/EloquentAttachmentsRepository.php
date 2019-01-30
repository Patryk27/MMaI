<?php

namespace App\Attachments\Implementation\Repositories;

use App\Attachments\Models\Attachment;
use App\Core\Repositories\EloquentRepository;
use Illuminate\Support\Collection;
use Throwable;

class EloquentAttachmentsRepository implements AttachmentsRepository {

    /** @var EloquentRepository */
    private $repository;

    public function __construct(Attachment $attachment) {
        $this->repository = new EloquentRepository($attachment);
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id): ?Attachment {
        return $this->repository->getById($id);
    }

    /**
     * @inheritDoc
     */
    public function getByPath(string $path): ?Attachment {
        return $this->repository->getBy('path', $path);
    }

    /**
     * @inheritDoc
     */
    public function getAll(): Collection {
        return $this->repository->getAll();
    }

    /**
     * @inheritDoc
     */
    public function getDetached(): Collection {
        return $this->repository->getByMany('attachable_id', null);
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function persist(Attachment $attachment): void {
        $this->repository->persist($attachment);
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function delete(Attachment $attachment): void {
        $this->repository->delete($attachment);
    }

}
