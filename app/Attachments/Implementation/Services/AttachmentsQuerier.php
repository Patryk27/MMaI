<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Exceptions\AttachmentException;
use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use App\Attachments\Queries\AttachmentsQuery;
use App\Attachments\Queries\GetAttachmentByIdQuery;
use App\Attachments\Queries\GetAttachmentByPathQuery;
use Illuminate\Support\Collection;

class AttachmentsQuerier
{
    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    public function __construct(AttachmentsRepository $attachmentsRepository)
    {
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param AttachmentsQuery $query
     * @return Collection|Attachment[]
     * @throws AttachmentException
     */
    public function query(AttachmentsQuery $query): Collection
    {
        switch (true) {
            case $query instanceof GetAttachmentByIdQuery:
                return collect_one(
                    $this->attachmentsRepository->getById(
                        $query->getId()
                    )
                );

            case $query instanceof GetAttachmentByPathQuery:
                return collect_one(
                    $this->attachmentsRepository->getByPath(
                        $query->getPath()
                    )
                );

            default:
                throw new AttachmentException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }
}
