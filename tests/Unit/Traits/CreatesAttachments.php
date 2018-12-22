<?php

namespace Tests\Unit\Traits;

use App\Attachments\Implementation\Repositories\InMemoryAttachmentsRepository;
use App\Attachments\Models\Attachment;
use Illuminate\Filesystem\FilesystemAdapter;

trait CreatesAttachments
{
    /** @var FilesystemAdapter */
    protected $attachmentsFs;

    /** @var InMemoryAttachmentsRepository */
    protected $attachmentsRepository;

    /**
     * Creates a fake attachment - both in the in-memory repository and the
     * in-memory filesystem.
     *
     * Can be used to prepare test fixtures.
     *
     * @param string $name
     * @return Attachment
     */
    protected function createAttachment(string $name): Attachment
    {
        $attachment = new Attachment([
            'name' => $name,
            'path' => $name,
        ]);

        $this->attachmentsRepository->persist($attachment);
        $this->attachmentsFs->put($name, $name);

        return $attachment;
    }
}
