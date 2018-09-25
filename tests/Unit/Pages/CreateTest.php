<?php

namespace Tests\Unit\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

class CreateTest extends TestCase
{

    /**
     * Since it does not make sense to create a page with no variants, let's
     * make sure that's forbidden.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testForbidsToCreatePageWithNoVariants(): void
    {
        $this->expectExceptionMessage('Each page must contain at least one variant.');

        $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_CMS,
            ],
        ]);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testFillsBasicProperties(): void
    {
        $page = $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_CMS,
                'notes' => 'some notes',
            ],

            'pageVariants' => [
                [
                    'language_id' => 100,
                    'status' => PageVariant::STATUS_DRAFT,
                    'title' => 'some title',
                    'lead' => 'some lead',
                    'content' => 'some content',
                ],
            ],
        ]);

        // Execute the page-related assertions
        $this->assertEquals(Page::TYPE_CMS, $page->type);
        $this->assertEquals('some notes', $page->notes);

        // Execute the page-variant-related assertions
        $pageVariant = $page->pageVariants[0];

        $this->assertEquals(100, $pageVariant->language_id);
        $this->assertEquals(PageVariant::STATUS_DRAFT, $pageVariant->status);
        $this->assertEquals('some title', $pageVariant->title);
        $this->assertEquals('some lead', $pageVariant->lead);
        $this->assertEquals('some content', $pageVariant->content);
    }

    /**
     * This test makes sure that the created page variant is assigned a route
     * when user passes one in the input.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testCreatesRouteWhenNecessary(): void
    {
        $page = $this->pagesFacade->create([
            'pageVariants' => [
                [
                    'url' => 'somewhere',
                ],
            ],
        ]);

        $pageVariant = $page->pageVariants->first();

        // Execute the assertions
        $this->assertNotNull($pageVariant);
        $this->assertNotNull($pageVariant->route);
        $this->assertEquals('somewhere', $pageVariant->route->url);
    }

    /**
     * This test makes sure that the created page variant is not assigned any
     * route, if user passes none in the input.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotCreateRouteWhenUnnecessary(): void
    {
        $page = $this->pagesFacade->create([
            'pageVariants' => [
                [
                    'language_id' => 100,
                ],
            ],
        ]);

        $pageVariant = $page->pageVariants[0];

        // Execute the assertions
        $this->assertNotNull($pageVariant);
        $this->assertEquals(100, $pageVariant->language_id);
        $this->assertNull($pageVariant->route);
    }

    /**
     * This test makes sure that the created page variant is assigned tags when
     * user passes some in the input.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testAddsTags(): void
    {
        /**
         * @var Collection|Tag[] $tags
         */
        $tags = $this->tagsRepository
            ->getAll()
            ->where('language_id', 100)
            ->values();

        $page = $this->pagesFacade->create([
            'pageVariants' => [
                [
                    'language_id' => 100,

                    'tag_ids' => [
                        $tags[0]->id,
                        $tags[1]->id,
                    ],
                ],
            ],
        ]);

        $pageVariant = $page->pageVariants[0];

        $this->assertCount(2, $pageVariant->tags);
        $this->assertEquals($tags[0], $pageVariant->tags[0]);
        $this->assertEquals($tags[1], $pageVariant->tags[1]);
    }

    /**
     * This test makes sure that one cannot create page variant with tags from
     * other languages - that is: when creating a Polish page, you cannot use
     * English tags.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testForbidsToAddTagFromOtherLanguage(): void
    {
        $this->expectExceptionMessage('Page variant cannot contain tags from other languages.');

        /**
         * @var Collection|Tag[] $tags
         */
        $tags = $this->tagsRepository
            ->getAll()
            ->where('language_id', 200)
            ->values();

        $this->pagesFacade->create([
            'pageVariants' => [
                [
                    'language_id' => 100,

                    'tag_ids' => [
                        $tags[0]->id,
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testAddsAttachments(): void
    {
        $attachmentA = $this->createAttachment('attachment-a');
        $attachmentB = $this->createAttachment('attachment-b');

        $page = $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_CMS,
            ],

            'pageVariants' => [
                [
                    'status' => PageVariant::STATUS_DRAFT,
                ],
            ],

            'attachment_ids' => [
                $attachmentA->id,
                $attachmentB->id,
            ],
        ]);

        $this->assertCount(2, $page->attachments);
        $this->assertEquals($attachmentA, $page->attachments[0]);
        $this->assertEquals($attachmentB, $page->attachments[1]);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testFailsOnNonExistingAttachment(): void
    {
        $this->expectExceptionMessage('Attachment was not found.');

        $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_CMS,
            ],

            'attachment_ids' => [
                100,
            ],
        ]);
    }

}
