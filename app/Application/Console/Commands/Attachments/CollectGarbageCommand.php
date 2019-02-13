<?php

namespace App\Application\Console\Commands\Attachments;

use App\Attachments\AttachmentsFacade;
use App\Attachments\Implementation\Services\AttachmentsGarbageCollector;
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

        if ($this->option('aggressive')) {
            $behaviour = AttachmentsGarbageCollector::BEHAVIOUR_AGGRESSIVE;
        } else {
            $behaviour = AttachmentsGarbageCollector::BEHAVIOUR_PEACEFUL;
        }

        $result = $this->attachmentsFacade->collectGarbage($behaviour);

        $this->output->writeln(sprintf(
            'Found <info>%d</info> detached attachments, <info>%d</info> got removed.',
            $result->getScannedAttachmentsCount(),
            $result->getRemovedAttachmentsCount()
        ));
    }

}
