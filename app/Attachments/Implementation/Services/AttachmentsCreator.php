<?php

namespace App\Attachments\Implementation\Services;

use App\Attachments\Implementation\Repositories\AttachmentsRepositoryInterface;
use App\Attachments\Models\Attachment;
use Illuminate\Contracts\Filesystem\Filesystem as FilesystemContract;
use Illuminate\Http\UploadedFile;
use Throwable;

class AttachmentsCreator
{

    /**
     * @var FilesystemContract
     */
    private $attachmentsFs;

    /**
     * @var AttachmentsRepositoryInterface
     */
    private $attachmentsRepository;

    /**
     * @param FilesystemContract $attachmentsFs
     * @param AttachmentsRepositoryInterface $attachmentsRepository
     */
    public function __construct(
        FilesystemContract $attachmentsFs,
        AttachmentsRepositoryInterface $attachmentsRepository
    ) {
        $this->attachmentsFs = $attachmentsFs;
        $this->attachmentsRepository = $attachmentsRepository;
    }

    /**
     * @param UploadedFile $file
     * @return Attachment
     *
     * @throws Throwable
     */
    public function createFromFile(UploadedFile $file): Attachment
    {
        $attachment = new Attachment([
            'name' => $file->getClientOriginalName() ?? $file->getFilename(),
            'size' => $file->getSize(),
            'mime' => $file->getMimeType(),

            'path' => bin2hex(
                random_bytes(32)
            ),
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
