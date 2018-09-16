<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Models\Attachment;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;

class AttachmentsStreamer
{

    /**
     * @var FilesystemContract
     */
    private $attachmentsFs;

    /**
     * @param FilesystemContract $attachmentsFs
     */
    public function __construct(
        FilesystemContract $attachmentsFs
    ) {
        $this->attachmentsFs = $attachmentsFs;
    }

    /**
     * @param Attachment $attachment
     * @return resource
     *
     * @throws FileNotFoundException
     */
    public function stream(Attachment $attachment)
    {
        return $this->attachmentsFs->readStream($attachment->path);
    }

}
