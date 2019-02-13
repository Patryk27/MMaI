<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use App\Attachments\ValueObjects\AttachmentsGarbageCollectorResult;
use Illuminate\Contracts\Filesystem\Filesystem;
use LogicException;

class AttachmentsGarbageCollector {

    public const
        BEHAVIOUR_AGGRESSIVE = 'aggressive',
        BEHAVIOUR_PEACEFUL = 'peaceful';

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
     * @param string $behaviour
     * @return AttachmentsGarbageCollectorResult
     */
    public function collectGarbage(string $behaviour): AttachmentsGarbageCollectorResult {
        $scannedAttachmentsCount = 0;
        $removedAttachmentsCount = 0;

        foreach ($this->attachmentsRepository->getDetached() as $attachment) {
            $scannedAttachmentsCount += 1;

            if (self::canCollect($attachment, $behaviour)) {
                $this->attachmentsDeleter->delete($attachment);
                $removedAttachmentsCount += 1;
            }
        }

        return new AttachmentsGarbageCollectorResult([
            'scannedAttachmentsCount' => $scannedAttachmentsCount,
            'removedAttachmentsCount' => $removedAttachmentsCount,
        ]);
    }

    /**
     * @param Attachment $attachment
     * @param string $behaviour
     * @return bool
     */
    private static function canCollect(Attachment $attachment, string $behaviour): bool {
        switch ($behaviour) {
            case self::BEHAVIOUR_AGGRESSIVE:
                return true;

            case self::BEHAVIOUR_PEACEFUL:
                return $attachment->created_at->diffInDays() >= 1;

            default:
                throw new LogicException(sprintf(
                    'Unknown behaviour: [%s].', $behaviour
                ));
        }
    }

}
