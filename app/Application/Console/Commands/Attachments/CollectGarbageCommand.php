<?php

namespace App\Application\Console\Commands\Attachments;

use App\Attachments\AttachmentsFacade;
use Illuminate\Console\Command;

final class CollectGarbageCommand extends Command {

    /** @var string */
    protected $signature = 'app:attachments:collect-garbage {--aggressive}';

    /** @var string */
    protected $description = 'Removes all the detached attachments from the filesystem.';

    /** @var AttachmentsFacade */
    private $attachmentsFacade;

    public function __construct(AttachmentsFacade $attachmentsFacade) {
        parent::__construct();

        $this->attachmentsFacade = $attachmentsFacade;
    }

    /**
     * @return void
     */
    public function handle(): void {
        $this->output->writeln('Removing detached attachments...');

        $result = $this->attachmentsFacade->collectGarbage(
            $this->option('aggressive') ?? false
        );

        $this->output->writeln(sprintf(
            'Scanned <info>%d</info> attachments, <info>%d</info> got removed.',
            $result->getScannedAttachmentsCount(),
            $result->getRemovedAttachmentsCount()
        ));
    }

}
