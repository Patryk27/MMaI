<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\ValueObjects\AttachmentsGarbageCollectorResult;
use Illuminate\Contracts\Filesystem\Filesystem;

class AttachmentsGarbageCollector {
    /** @var Filesystem */
    private $attachmentsFs;

    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    /** @var AttachmentsDeleter */
    private $attachmentsDeleter;

    public function __construct(
        Filesystem $attachmentsFs,
        AttachmentsRepository $attachmentsRepository,
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
    public function collectGarbage(bool $aggressive): AttachmentsGarbageCollectorResult {
        $scannedAttachmentsCount = 0;
        $removedAttachmentsCount = 0;

        foreach ($this->attachmentsRepository->getDetached() as $attachment) {
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
