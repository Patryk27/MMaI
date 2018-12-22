<?php

namespace Tests\Unit\Attachments;

use App\Attachments\AttachmentsFacade;
use App\Attachments\AttachmentsFactory;
use App\Attachments\Implementation\Repositories\InMemoryAttachmentsRepository;
use App\Core\Repositories\InMemoryRepository;
use Illuminate\Filesystem\FilesystemAdapter;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Tests\Unit\TestCase as BaseTestCase;
use Tests\Unit\Traits\CreatesAttachments;

abstract class TestCase extends BaseTestCase
{
    use CreatesAttachments;

    /** @var FilesystemAdapter */
    protected $attachmentsFs;

    /** @var InMemoryAttachmentsRepository */
    protected $attachmentsRepository;

    /** @var AttachmentsFacade */
    protected $attachmentsFacade;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->attachmentsFs = new FilesystemAdapter(
            new Filesystem(
                new MemoryAdapter()
            )
        );

        $this->attachmentsRepository = new InMemoryAttachmentsRepository(
            new InMemoryRepository()
        );

        $this->attachmentsFacade = AttachmentsFactory::build(
            $this->attachmentsFs,
            $this->attachmentsRepository
        );
    }
}
