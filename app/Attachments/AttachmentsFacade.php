<?php

namespace App\Attachments;

use App\Attachments\Exceptions\AttachmentException;
use App\Attachments\Exceptions\AttachmentNotFoundException;
use App\Attachments\Implementation\Services\AttachmentsCreator;
use App\Attachments\Implementation\Services\AttachmentsGarbageCollector;
use App\Attachments\Implementation\Services\AttachmentsQuerier;
use App\Attachments\Implementation\Services\AttachmentsStreamer;
use App\Attachments\Implementation\Services\AttachmentsUpdater;
use App\Attachments\Models\Attachment;
use App\Attachments\Queries\AttachmentsQuery;
use App\Attachments\Requests\CreateAttachment;
use App\Attachments\Requests\UpdateAttachment;
use App\Attachments\ValueObjects\AttachmentsGarbageCollectorResult;
use Illuminate\Support\Collection;
use Throwable;

final class AttachmentsFacade {

    /** @var AttachmentsCreator */
    private $attachmentsCreator;

    /** @var AttachmentsUpdater */
    private $attachmentsUpdater;

    /** @var AttachmentsGarbageCollector */
    private $attachmentsGarbageCollector;

    /** @var AttachmentsStreamer */
    private $attachmentsStreamer;

    /** @var AttachmentsQuerier */
    private $attachmentsQuerier;

    public function __construct(
        AttachmentsCreator $attachmentsCreator,
        AttachmentsUpdater $attachmentsUpdater,
        AttachmentsGarbageCollector $attachmentsGarbageCollector,
        AttachmentsStreamer $attachmentsStreamer,
        AttachmentsQuerier $attachmentsQuerier
    ) {
        $this->attachmentsCreator = $attachmentsCreator;
        $this->attachmentsUpdater = $attachmentsUpdater;
        $this->attachmentsGarbageCollector = $attachmentsGarbageCollector;
        $this->attachmentsStreamer = $attachmentsStreamer;
        $this->attachmentsQuerier = $attachmentsQuerier;
    }

    /**
     * Creates a detached attachment which should be manually linked to
     * appropriate page later.
     *
     * @param CreateAttachment $request
     * @return Attachment
     * @throws Throwable
     */
    public function create(CreateAttachment $request): Attachment {
        return $this->attachmentsCreator->create($request);
    }

    /**
     * Updates specified attachment in the database.
     *
     * @param Attachment $attachment
     * @param UpdateAttachment $request
     * @return void
     */
    public function update(Attachment $attachment, UpdateAttachment $request): void {
        $this->attachmentsUpdater->update($attachment, $request);
    }

    /**
     * Removes all the detached attachments from the filesystem.
     *
     * @see AttachmentsGarbageCollector::BEHAVIOUR_PEACEFUL
     * @see AttachmentsGarbageCollector::BEHAVIOUR_AGGRESSIVE
     *
     * @param string $behaviour
     * @return AttachmentsGarbageCollectorResult
     */
    public function collectGarbage(string $behaviour): AttachmentsGarbageCollectorResult {
        return $this->attachmentsGarbageCollector->collectGarbage($behaviour);
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
     * @throws AttachmentNotFoundException
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
     */
    public function queryMany(AttachmentsQuery $query): Collection {
        return $this->attachmentsQuerier->query($query);
    }

}
