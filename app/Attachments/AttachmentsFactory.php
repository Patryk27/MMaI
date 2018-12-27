<?php

namespace App\Attachments;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Implementation\Services\AttachmentsCreator;
use App\Attachments\Implementation\Services\AttachmentsDeleter;
use App\Attachments\Implementation\Services\AttachmentsGarbageCollector;
use App\Attachments\Implementation\Services\AttachmentsQuerier;
use App\Attachments\Implementation\Services\AttachmentsStreamer;
use Illuminate\Contracts\Filesystem\Filesystem as Filesystem;

final class AttachmentsFactory
{
    /**
     * Builds an instance of @see AttachmentsFacade.
     *
     * @param Filesystem $attachmentsFs
     * @param AttachmentsRepository $attachmentsRepository
     * @return AttachmentsFacade
     */
    public static function build(
        Filesystem $attachmentsFs,
        AttachmentsRepository $attachmentsRepository
    ): AttachmentsFacade {
        $attachmentsCreator = new AttachmentsCreator($attachmentsFs, $attachmentsRepository);
        $attachmentsDeleter = new AttachmentsDeleter($attachmentsFs, $attachmentsRepository);
        $attachmentsGarbageCollector = new AttachmentsGarbageCollector($attachmentsFs, $attachmentsRepository, $attachmentsDeleter);
        $attachmentsPathGenerator = new AttachmentsStreamer($attachmentsFs);
        $attachmentsQuerier = new AttachmentsQuerier($attachmentsRepository);

        return new AttachmentsFacade(
            $attachmentsCreator,
            $attachmentsGarbageCollector,
            $attachmentsPathGenerator,
            $attachmentsQuerier
        );
    }
}
