<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Attachments\AttachmentsFacade;
use App\Attachments\Exceptions\AttachmentException;
use App\Attachments\Queries\GetAttachmentByPathQuery;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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
     * @param string $path
     * @return mixed
     */
    public function attachment(string $path)
    {
        try {
            $attachment = $this->attachmentsFacade->queryOne(
                new GetAttachmentByPathQuery($path)
            );
        } catch (AttachmentException $ex) {
            throw new NotFoundHttpException('This attachment could not have been found.');
        }

        return response()->streamDownload(function () use ($attachment) {
            return $this->attachmentsFacade->stream($attachment);
        }, $attachment->name);
    }

}
