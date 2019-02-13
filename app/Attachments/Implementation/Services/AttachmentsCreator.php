<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepository;
use App\Attachments\Models\Attachment;
use App\Attachments\Requests\CreateAttachment;
use Illuminate\Contracts\Filesystem\Filesystem;
use Throwable;

class AttachmentsCreator {

    /** @var Filesystem */
    private $attachmentsFs;

    /** @var AttachmentsRepository */
    private $attachmentsRepository;

    public function __construct(
        Filesystem $attachmentsFs,
        AttachmentsRepository $attachmentsRepository
    ) {
        $this->attachmentsFs = $attachmentsFs;
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param CreateAttachment $request
     * @return Attachment
     * @throws Throwable
     */
    public function create(CreateAttachment $request): Attachment {
        $file = $request->file('attachment');

        $attachment = new Attachment([
            'name' => $file->getClientOriginalName() ?? $file->getFilename(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),
            'path' => bin2hex(random_bytes(32)),
        ]);

        $this->attachmentsFs->put($attachment->path, $file->get());

        try {
            $this->attachmentsRepository->persist($attachment);
        } catch (Throwable $ex) {
            $this->attachmentsFs->delete($attachment->path);
            throw $ex;
        }

        return $attachment;
    }

}
