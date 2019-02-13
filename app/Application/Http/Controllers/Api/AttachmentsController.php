<?php

namespace App\Application\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;
use App\Attachments\AttachmentsFacade;
use App\Attachments\Models\Attachment;
use App\Attachments\Requests\CreateAttachment;
use App\Attachments\Requests\UpdateAttachment;
use Throwable;

class AttachmentsController extends Controller {

    /** @var AttachmentsFacade */
    private $attachmentsFacade;

    public function __construct(AttachmentsFacade $attachmentsFacade) {
        $this->attachmentsFacade = $attachmentsFacade;
    }

    /**
     * @param CreateAttachment $request
     * @return Attachment
     * @throws Throwable
     */
    public function store(CreateAttachment $request): Attachment {
        return $this->attachmentsFacade->create($request);
    }

    /**
     * @param Attachment $attachment
     * @param UpdateAttachment $request
     * @return Attachment
     */
    public function update(Attachment $attachment, UpdateAttachment $request): Attachment {
        $this->attachmentsFacade->update($attachment, $request);

        return $attachment;
    }

}
