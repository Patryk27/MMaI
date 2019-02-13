<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use App\Attachments\Queries\AttachmentsQuery;
use App\Attachments\Queries\GetAttachmentById;
use App\Attachments\Queries\GetAttachmentByPath;
use Illuminate\Support\Collection;
use LogicException;

class AttachmentsQuerier {

    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    public function __construct(AttachmentsRepository $attachmentsRepository) {
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param AttachmentsQuery $query
     * @return Collection|Attachment[]
     */
    public function query(AttachmentsQuery $query): Collection {
        switch (true) {
            case $query instanceof GetAttachmentById:
                return collect_one(
                    $this->attachmentsRepository->getById(
                        $query->getId()
                    )
                );

            case $query instanceof GetAttachmentByPath:
                return collect_one(
                    $this->attachmentsRepository->getByPath(
                        $query->getPath()
                    )
                );

            default:
                throw new LogicException(sprintf(
                    'Cannot handle query of class [%s].', get_class($query)
                ));
        }
    }

}
