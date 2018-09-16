<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Exceptions\AttachmentException;
use App\Attachments\Implementation\Repositories\AttachmentsRepositoryInterface;
use App\Attachments\Models\Attachment;
use App\Attachments\Queries\AttachmentsQueryInterface;
use App\Attachments\Queries\GetAttachmentByIdQuery;
use Illuminate\Support\Collection;

class AttachmentsQuerier
{

    /**
     * @var AttachmentsRepositoryInterface
     */
    private $attachmentsRepository;

    /**
     * @param AttachmentsRepositoryInterface $attachmentsRepository
     */
    public function __construct(
        AttachmentsRepositoryInterface $attachmentsRepository
    ) {
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param AttachmentsQueryInterface $query
     * @return Collection|Attachment[]
     *
     * @throws AttachmentException
     */
    public function query(AttachmentsQueryInterface $query): Collection
    {
        switch (true) {
            case $query instanceof GetAttachmentByIdQuery:
                return collect_one(
                    $this->attachmentsRepository->getById(
                        $query->getId()
                    )
                );

            default:
                throw new AttachmentException(
                    sprintf('Cannot handle query of class [%s].', get_class($query))
                );
        }
    }

}
