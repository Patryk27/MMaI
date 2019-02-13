<?php

namespace App\Attachments;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Implementation\Services\AttachmentsCreator;
use App\Attachments\Implementation\Services\AttachmentsDeleter;
use App\Attachments\Implementation\Services\AttachmentsGarbageCollector;
use App\Attachments\Implementation\Services\AttachmentsQuerier;
use App\Attachments\Implementation\Services\AttachmentsStreamer;
use App\Attachments\Implementation\Services\AttachmentsUpdater;
use Illuminate\Contracts\Filesystem\Filesystem as Filesystem;

final class AttachmentsFactory {

    public static function build(
        Filesystem $attachmentsFs,
        AttachmentsRepository $attachmentsRepository
    ): AttachmentsFacade {
        $attachmentsCreator = new AttachmentsCreator($attachmentsFs, $attachmentsRepository);
        $attachmentsUpdater = new AttachmentsUpdater($attachmentsRepository);
        $attachmentsDeleter = new AttachmentsDeleter($attachmentsFs, $attachmentsRepository);
        $attachmentsGarbageCollector = new AttachmentsGarbageCollector($attachmentsFs, $attachmentsRepository, $attachmentsDeleter);
        $attachmentsPathGenerator = new AttachmentsStreamer($attachmentsFs);
        $attachmentsQuerier = new AttachmentsQuerier($attachmentsRepository);

        return new AttachmentsFacade(
            $attachmentsCreator,
            $attachmentsUpdater,
            $attachmentsGarbageCollector,
            $attachmentsPathGenerator,
            $attachmentsQuerier
        );
    }

}
