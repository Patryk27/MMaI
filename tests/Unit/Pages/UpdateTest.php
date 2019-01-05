<?php

namespace Tests\Unit\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Tags\Models\Tag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class UpdateTest extends TestCase {
    /** @var Page */
    private $page;

    /**
     * @inheritdoc
     */
    public function setUp(): void {
        parent::setUp();

        /** @var Collection|Tag[] $tags */
        $tags = $this->tagsRepository
            ->getAll()
            ->where('language_id', 100)
            ->values();

        $this->page = new Page([
            'language_id' => 100,

            'title' => 'some title',
            'lead' => 'some lead',
            'content' => 'some content',
            'notes' => 'some notes',

            'type' => Page::TYPE_PAGE,
            'status' => Page::STATUS_DRAFT,
        ]);

        $this->page->setRelations([
            'tags' => new EloquentCollection([
                $tags[0],
            ]),
        ]);

        $this->pagesRepository->persist($this->page);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testUpdatesBasicProperties(): void {
        $this->pagesFacade->update($this->page, [
            'lead' => 'some updated lead',
            'notes' => 'some updated notes',
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertEquals('some updated lead', $this->page->lead);
        $this->assertEquals('some updated notes', $this->page->notes);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testDoesNotAllowToChangeLanguage(): void {
        $this->pagesFacade->update($this->page, [
            'language_id' => 150,
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertEquals(100, $this->page->language_id);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testSetsPublishedAt(): void {
        $this->pagesFacade->update($this->page, [
            'url' => 'somewhere',
            'status' => Page::STATUS_PUBLISHED,
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Make sure it has been filled the values we provided
        $this->assertNotNull($this->page->route);
        $this->assertEquals(Page::STATUS_PUBLISHED, $this->page->status);

        // Make sure update() automatically set the "published at" property
        $this->assertNotNull($this->page->published_at);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testCreatesRoute(): void {
        $this->pagesFacade->update($this->page, [
            'url' => 'somewhere',
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertNotNull($this->page->route);
        $this->assertEquals('somewhere', $this->page->route->url);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testUpdatesRoute(): void {
        // Step 1: create a new route (so that we will be able to update it
        // later)
        $this->pagesFacade->update($this->page, [
            'url' => 'somewhere',
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Step 2: remove that route
        $this->pagesFacade->update($this->page, [
            'url' => 'somewhere-else',
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertNotNull($this->page->route);
        $this->assertEquals('somewhere-else', $this->page->route->url);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testDeletesRoute(): void {
        // Step 1: create a new route (so that we will be able to remove it
        // later)
        $this->pagesFacade->update($this->page, [
            'url' => 'somewhere',
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Step 2: remove that route
        $this->pagesFacade->update($this->page, [
            'url' => '',
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertNull($this->page->route);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testForbidsToPublishPageWithoutRoute(): void {
        $this->expectExceptionMessage('Published page must have a route.');

        $this->pagesFacade->update($this->page, [
            'status' => Page::STATUS_PUBLISHED,
        ]);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testForbidsToPublishPostWithoutLead(): void {
        $this->expectExceptionMessage('Published post must contain a lead.');

        // Step 1: create an example post
        $page = $this->pagesFacade->create([
            'type' => Page::TYPE_POST,
        ]);

        // Step 2: try to publish it
        $this->pagesFacade->update($page, [
            'url' => 'somewhere',
            'status' => Page::STATUS_PUBLISHED,
        ]);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testAddsTags(): void {
        /** @var Collection|Tag[] $tags */
        $tags = $this->tagsRepository
            ->getAll()
            ->where('language_id', 100)
            ->values();

        $this->pagesFacade->update($this->page, [
            'tag_ids' => [
                $tags[0]->id,
                $tags[1]->id,
            ],
        ]);

        $this->assertCount(2, $this->page->tags);
        $this->assertEquals($tags[0], $this->page->tags[0]);
        $this->assertEquals($tags[1], $this->page->tags[1]);
    }

    /**
     * @return void
     * @throws AppException
     */
    public function testRemovesTags(): void {
        $this->pagesFacade->update($this->page, [
            'tag_ids' => [],
        ]);

        $this->assertCount(0, $this->page->tags);
    }

    /**
     * @throws AppException
     */
    public function testAddsAttachments(): void {
        $attachmentA = $this->createAttachment('attachment-a');
        $attachmentB = $this->createAttachment('attachment-b');

        $this->pagesFacade->update($this->page, [
            'attachment_ids' => [
                $attachmentA->id,
                $attachmentB->id,
            ],
        ]);

        $this->assertCount(2, $this->page->attachments);
        $this->assertEquals($attachmentA, $this->page->attachments[0]);
        $this->assertEquals($attachmentB, $this->page->attachments[1]);
    }

    /**
     * @throws AppException
     */
    public function testRemovesAttachments(): void {
        $attachmentA = $this->createAttachment('attachment-a');
        $attachmentB = $this->createAttachment('attachment-b');

        $this->pagesFacade->update($this->page, [
            'attachment_ids' => [
                $attachmentA->id,
                $attachmentB->id,
            ],
        ]);

        $this->pagesFacade->update($this->page, [
            'attachment_ids' => [
                $attachmentB->id,
            ],
        ]);

        $this->assertCount(1, $this->page->attachments);
        $this->assertEquals($attachmentB, $this->page->attachments[0]);
    }
}
