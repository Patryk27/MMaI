<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use App\Attachments\Requests\UpdateAttachment;

class AttachmentsUpdater {

    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    public function __construct(AttachmentsRepository $attachmentsRepository) {
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param Attachment $attachment
     * @param UpdateAttachment $request
     * @return void
     */
    public function update(Attachment $attachment, UpdateAttachment $request): void {
        $attachment->name = $request->get('name');

        $this->attachmentsRepository->persist($attachment);
    }

}
