<?php

namespace App\Attachments;

use App\Attachments\Exceptions\AttachmentException;
use App\Attachments\Exceptions\AttachmentNotFoundException;
use App\Attachments\Implementation\Services\AttachmentsCreator;
use App\Attachments\Implementation\Services\AttachmentsGarbageCollector;
use App\Attachments\Implementation\Services\AttachmentsQuerier;
use App\Attachments\Implementation\Services\AttachmentsStreamer;
use App\Attachments\Models\Attachment;
use App\Attachments\Queries\AttachmentsQuery;
use App\Attachments\ValueObjects\AttachmentsGarbageCollectorResult;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Throwable;

final class AttachmentsFacade {
    /** @var AttachmentsCreator */
    private $attachmentsCreator;

    /** @var AttachmentsGarbageCollector */
    private $attachmentsGarbageCollector;

    /** @var AttachmentsStreamer */
    private $attachmentsStreamer;

    /** @var AttachmentsQuerier */
    private $attachmentsQuerier;

    public function __construct(
        AttachmentsCreator $attachmentsCreator,
        AttachmentsGarbageCollector $attachmentsGarbageCollector,
        AttachmentsStreamer $attachmentsStreamer,
        AttachmentsQuerier $attachmentsQuerier
    ) {
        $this->attachmentsCreator = $attachmentsCreator;
        $this->attachmentsGarbageCollector = $attachmentsGarbageCollector;
        $this->attachmentsStreamer = $attachmentsStreamer;
        $this->attachmentsQuerier = $attachmentsQuerier;
    }

    /**
     * Creates a detached attachment.
     *
     * @param UploadedFile $file
     * @return Attachment
     * @throws Throwable
     */
    public function createFromFile(UploadedFile $file): Attachment {
        return $this->attachmentsCreator->createFromFile($file);
    }

    /**
     * Removes all the detached attachments from the filesystem.
     *
     * Should be run e.g. each day to make sure there are no unused left-overs
     * in the storage.
     *
     * When $aggressive is set to `false`, only detached attachments older than
     * 1 day are removed.
     *
     * When $aggressive is set to `true`, all the detached attachments are
     * removed (ignoring timestamps).
     *
     * @param bool $aggressive
     * @return AttachmentsGarbageCollectorResult
     */
    public function collectGarbage(bool $aggressive = false): AttachmentsGarbageCollectorResult {
        return $this->attachmentsGarbageCollector->collectGarbage($aggressive);
    }

    /**
     * Returns a stream which reads given attachment.
     *
     * @param Attachment $attachment
     * @return resource
     * @throws AttachmentException
     */
    public function stream(Attachment $attachment) {
        return $this->attachmentsStreamer->stream($attachment);
    }

    /**
     * Returns the first attachment matching given query.
     * Throws an exception if no such attachment exists.
     *
     * @param AttachmentsQuery $query
     * @return Attachment
     * @throws AttachmentException
     */
    public function queryOne(AttachmentsQuery $query): Attachment {
        $attachments = $this->queryMany($query);

        if ($attachments->isEmpty()) {
            throw new AttachmentNotFoundException();
        }

        return $attachments->first();
    }

    /**
     * Returns all the attachments matching given query.
     *
     * @param AttachmentsQuery $query
     * @return Collection|Attachment[]
     * @throws AttachmentException
     */
    public function queryMany(AttachmentsQuery $query): Collection {
        return $this->attachmentsQuerier->query($query);
    }
}
