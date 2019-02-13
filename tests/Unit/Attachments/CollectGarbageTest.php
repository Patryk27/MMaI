<?php

namespace Tests\Unit\Attachments;

use App\Attachments\Implementation\Services\AttachmentsGarbageCollector;
use App\Attachments\Models\Attachment;
use Carbon\Carbon;

class CollectGarbageTest extends TestCase {

    /** @var Attachment[] */
    private $attachments;

    /**
     * @inheritdoc
     */
    public function setUp(): void {
        parent::setUp();

        // Create an attachment bound to a post; such attachment should never be
        // picked up by the garbage collector
        $boundAttachment = $this->createAttachment('bound');
        $boundAttachment->page_id = 100;

        // Create a fresh attachment, created just now, which should be picked
        // up only by the aggressive collector
        $unboundFreshAttachment = $this->createAttachment('unbound-fresh');

        // Create a one-month-old attachment that should be picked up by both
        // garbage collectors
        $unboundOldAttachment = $this->createAttachment('unbound-old');
        $unboundOldAttachment->created_at = Carbon::now()->subMonth();

        $this->attachments = [
            'bound' => $boundAttachment,
            'unbound-fresh' => $unboundFreshAttachment,
            'unbound-old' => $unboundOldAttachment,
        ];

        foreach ($this->attachments as $attachment) {
            $this->attachmentsRepository->persist($attachment);
        }
    }

    /**
     * @return void
     */
    public function testPeacefulCollector(): void {
        $this->attachmentsFacade->collectGarbage(AttachmentsGarbageCollector::BEHAVIOUR_PEACEFUL);

        $attachments = $this->attachmentsRepository->getAll();

        $this->assertCount(2, $attachments);
        $this->assertEquals($this->attachments['bound'], $attachments[$this->attachments['bound']->id]);
        $this->assertEquals($this->attachments['unbound-fresh'], $attachments[$this->attachments['unbound-fresh']->id]);
    }

    /**
     * @return void
     */
    public function testAggressiveCollector(): void {
        $this->attachmentsFacade->collectGarbage(AttachmentsGarbageCollector::BEHAVIOUR_AGGRESSIVE);

        $attachments = $this->attachmentsRepository->getAll();

        $this->assertCount(1, $attachments);
        $this->assertEquals($this->attachments['bound'], $attachments[$this->attachments['bound']->id]);
    }

}
