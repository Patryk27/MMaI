<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use Illuminate\Contracts\Filesystem\Filesystem as Filesystem;

class AttachmentsDeleter {

    /** @var Filesystem */
    private $attachmentsFs;

    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    public function __construct(
        Filesystem $attachmentsFs,
        AttachmentsRepository $attachmentsRepository
    ) {
        $this->attachmentsFs = $attachmentsFs;
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param Attachment $attachment
     * @return void
     */
    public function delete(Attachment $attachment): void {
        $this->attachmentsFs->delete($attachment->path);
        $this->attachmentsRepository->delete($attachment);
    }

}
