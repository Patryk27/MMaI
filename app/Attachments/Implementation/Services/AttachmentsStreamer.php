<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Exceptions\AttachmentException;
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
     * @throws AttachmentException
     */
    public function stream(Attachment $attachment)
    {
        try {
            $stream = $this->attachmentsFs->readStream($attachment->path);
        } catch (FileNotFoundException $ex) {
            throw new AttachmentException(
                sprintf('Attachment [id=%d] was not found in the storage.', $attachment->id)
            );
        }

        if (is_null($stream)) {
            throw new AttachmentException(
                sprintf('Failed to initialize stream for attachment [id=%d].', $attachment->id)
            );
        }

        return $stream;
    }

}
