<?php

namespace App\Attachments\ValueObjects;

use App\Core\ValueObjects\HasInitializationConstructor;

final class AttachmentsGarbageCollectorResult
{

    use HasInitializationConstructor;

    /**
     * @var int
     */
    private $scannedAttachmentsCount;

    /**
     * @var int
     */
    private $removedAttachmentsCount;

    /**
     * @return int
     */
    public function getScannedAttachmentsCount(): int
    {
        return $this->scannedAttachmentsCount;
    }

    /**
     * @return int
     */
    public function getRemovedAttachmentsCount(): int
    {
        return $this->removedAttachmentsCount;
    }

}
