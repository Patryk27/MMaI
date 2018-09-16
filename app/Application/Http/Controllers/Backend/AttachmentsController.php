<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Attachments\CreateAttachmentRequest;
use App\Attachments\AttachmentsFacade;
use App\Attachments\Models\Attachment;
use Throwable;

class AttachmentsController extends Controller
{

    /**
     * @var AttachmentsFacade
     */
    private $attachmentsFacade;

    /**
     * @param AttachmentsFacade $attachmentsFacade
     */
    public function __construct(
        AttachmentsFacade $attachmentsFacade
    ) {
        $this->attachmentsFacade = $attachmentsFacade;
    }

    /**
     * @param CreateAttachmentRequest $request
     * @return array
     *
     * @throws Throwable
     */
    public function store(CreateAttachmentRequest $request): array
    {
        $attachment = $this->attachmentsFacade->createFromFile(
            $request->file('attachment')
        );

        return [
            'id' => $attachment->id,
            'name' => $attachment->name,
            'size' => $attachment->getSizeForHumans(),
        ];
    }

    /**
     * @param Attachment $attachment
     * @return mixed
     */
    public function download(Attachment $attachment)
    {
        return response()->streamDownload(function () use ($attachment) {
            return $this->attachmentsFacade->stream($attachment);
        }, $attachment->name);
    }

}
