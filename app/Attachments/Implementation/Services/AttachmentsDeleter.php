<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class AttachmentsDeleter
{
    /** @var FilesystemContract */
    private $attachmentsFs;

    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    public function __construct(
        FilesystemContract $attachmentsFs,
        AttachmentsRepository $attachmentsRepository
    ) {
        $this->attachmentsFs = $attachmentsFs;
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param Attachment $attachment
     * @return void
     */
    public function delete(Attachment $attachment): void
    {
        $this->attachmentsFs->delete($attachment->path);
        $this->attachmentsRepository->delete($attachment);
    }
}
