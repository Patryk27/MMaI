<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepositoryInterface;
use App\Attachments\ValueObjects\AttachmentsGarbageCollectorResult;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class AttachmentsGarbageCollector
{

    /**
     * @var FilesystemContract
     */
    private $attachmentsFs;

    /**
     * @var AttachmentsRepositoryInterface
     */
    private $attachmentsRepository;

    /**
     * @var AttachmentsDeleter
     */
    private $attachmentsDeleter;

    /**
     * @param FilesystemContract $attachmentsFs
     * @param AttachmentsRepositoryInterface $attachmentsRepository
     * @param AttachmentsDeleter $attachmentsDeleter
     */
    public function __construct(
        FilesystemContract $attachmentsFs,
        AttachmentsRepositoryInterface $attachmentsRepository,
        AttachmentsDeleter $attachmentsDeleter
    ) {
        $this->attachmentsFs = $attachmentsFs;
        $this->attachmentsRepository = $attachmentsRepository;
        $this->attachmentsDeleter = $attachmentsDeleter;
    }

    /**
     * @param bool $aggressive
     * @return AttachmentsGarbageCollectorResult
     */
    public function collectGarbage(bool $aggressive): AttachmentsGarbageCollectorResult
    {
        $scannedAttachmentsCount = 0;
        $removedAttachmentsCount = 0;

        foreach ($this->attachmentsRepository->getAllUnbound() as $attachment) {
            $scannedAttachmentsCount += 1;

            if ($aggressive || $attachment->created_at->diffInDays() >= 1) {
                $this->attachmentsDeleter->delete($attachment);

                $removedAttachmentsCount += 1;
            }
        }

        return new AttachmentsGarbageCollectorResult([
            'scannedAttachmentsCount' => $scannedAttachmentsCount,
            'removedAttachmentsCount' => $removedAttachmentsCount,
        ]);
    }

}
