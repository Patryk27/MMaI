<?php

namespace App\Application\Interfaces\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Attachments\AttachmentsFacade;
use App\Attachments\Exceptions\AttachmentNotFoundException;
use App\Attachments\Queries\GetAttachmentByPath;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AttachmentsController extends Controller {

    /** @var AttachmentsFacade */
    private $attachmentsFacade;

    public function __construct(AttachmentsFacade $attachmentsFacade) {
        $this->attachmentsFacade = $attachmentsFacade;
    }

    /**
     * @param string $path
     * @return mixed
     */
    public function attachment(string $path) {
        try {
            $attachment = $this->attachmentsFacade->queryOne(
                new GetAttachmentByPath($path)
            );
        } catch (AttachmentNotFoundException $ex) {
            throw new NotFoundHttpException('This attachment could not have been found.');
        }

        return response()->streamDownload(function () use ($attachment): void {
            $stream = $this->attachmentsFacade->stream($attachment);

            while ($chunk = fread($stream, 1024 * 1024)) {
                echo $chunk;
            }
        }, $attachment->name, [
            'Content-Type' => $attachment->mime,
        ]);
    }

}
