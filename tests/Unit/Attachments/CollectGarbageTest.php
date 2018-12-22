<?php

namespace Tests\Unit\Attachments;

use App\Attachments\Models\Attachment;
use Carbon\Carbon;

/**
 * @see \App\Attachments\Implementation\Services\AttachmentsGarbageCollector
 */
class CollectGarbageTest extends TestCase
{
    /** @var Attachment[] */
    private $attachments;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->attachments = [
            'bound' => $this->createAttachment('bound'),
            'unbound-new' => $this->createAttachment('unbound-new'),
            'unbound-old' => $this->createAttachment('unbound-old'),
        ];

        // Attach the "bound" attachment to something
        $this->attachments['bound']->attachable_type = 'something';
        $this->attachments['bound']->attachable_id = 100;

        // Change the "unbound-old" attachment so that it's actually old
        $this->attachments['unbound-old']->created_at = Carbon::now()->subMonth();

        // Re-save all the attachments, since we have just modified a few
        foreach ($this->attachments as $attachment) {
            $this->attachmentsRepository->persist($attachment);
        }
    }

    /**
     * The "non-aggressive" garbage collector should delete only the
     * "unbound-old" attachment - this test makes sure that happens.
     *
     * @return void
     */
    public function testNonAggressive(): void
    {
        // Execute the garbage collector
        $this->attachmentsFacade->collectGarbage(false);

        // Make sure we're left with the "bound" and "unbound-new" attachments
        // only
        $attachments = $this->attachmentsRepository->getAll();

        $this->assertCount(2, $attachments);
        $this->assertEquals($this->attachments['bound'], $attachments[$this->attachments['bound']->id]);
        $this->assertEquals($this->attachments['unbound-new'], $attachments[$this->attachments['unbound-new']->id]);
    }

    /**
     * The "aggressive" garbage collector should delete both the unbound
     * attachments - this test makes sure that happens.
     *
     * @return void
     */
    public function testAggressive(): void
    {
        // Execute the garbage collector
        $this->attachmentsFacade->collectGarbage(true);

        // Make sure we're left with the "bound" attachment only
        $attachments = $this->attachmentsRepository->getAll();

        $this->assertCount(1, $attachments);
        $this->assertEquals($this->attachments['bound'], $attachments[$this->attachments['bound']->id]);
    }
}
