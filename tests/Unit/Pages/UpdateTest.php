<?php

namespace Tests\Unit\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Tags\Models\Tag;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class UpdateTest extends TestCase
{

    /**
     * @var Page
     */
    private $page;

    /**
     * @var PageVariant
     */
    private $pageVariant;

    /**
     * @inheritdoc
     */
    public function setUp(): void
    {
        parent::setUp();

        /**
         * @var Collection|Tag[] $tags
         */
        $tags = $this->tagsRepository
            ->getAll()
            ->where('language_id', 100)
            ->values();

        // Let's build an example page, so that we have something we can update
        $this->page = new Page([
            'type' => Page::TYPE_CMS,
            'notes' => 'some notes',
        ]);

        $this->pageVariant = new PageVariant([
            'language_id' => 100,
            'status' => PageVariant::STATUS_DRAFT,
            'title' => 'some title',
            'lead' => 'some lead',
            'content' => 'some content',
        ]);

        $this->pageVariant->setRelations([
            'tags' => new EloquentCollection([
                $tags[0],
            ]),
        ]);

        $this->page->pageVariants->push($this->pageVariant);

        $this->pagesRepository->persist($this->page);
    }

    /**
     * This test makes sure that the update() method correctly updates all the
     * basic page properties.
     *
     * Since it's just `$page->notes` for now, this does not do too much.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testUpdatesBasicProperties(): void
    {
        $this->pagesFacade->update($this->page, [
            'page' => [
                'notes' => 'some updated notes',
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertEquals('some updated notes', $this->page->notes);
    }

    /**
     * This test makes sure that the update() method gracefully fails when it is
     * told to update a page variant with non-existing id.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testFailsOnNonExistingPageVariant(): void
    {
        $this->expectExceptionMessage('was not found inside page');

        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => 123,
                ],
            ],
        ]);
    }

    /**
     * This test makes sure that the update() method properly creates a new
     * page variant, if it is given a page variant without id.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testCreatesNewPageVariant(): void
    {
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'language_id' => 200,
                    'status' => PageVariant::STATUS_DRAFT,
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Make sure update() created a new page variant
        $this->assertCount(2, $this->page->pageVariants);

        // Make sure that newly-created page variant has everything filled correctly
        $pageVariant = $this->page->pageVariants[1];

        $this->assertEquals(200, $pageVariant->language_id);
        $this->assertEquals(PageVariant::STATUS_DRAFT, $pageVariant->status);
    }

    /**
     * This test makes sure that the update() method properly updates basic
     * properties of an already existing page variant.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testUpdatesExistingPageVariant(): void
    {
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'lead' => 'some updated lead',
                ],
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Make sure update() did not create any new page variant
        $this->assertCount(1, $this->page->pageVariants);

        // Make sure appropriate values were updated
        $pageVariant = $this->page->pageVariants[0];

        $this->assertEquals(100, $pageVariant->language_id);
        $this->assertEquals('some updated lead', $pageVariant->lead);
    }

    /**
     * This test makes sure that the update() method does not allow to change
     * language of an already existing page variant.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDoesNotChangeLanguage(): void
    {
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'language_id' => 150,
                ],
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Make sure the language has not been changed
        $this->assertCount(1, $this->page->pageVariants);
        $this->assertEquals(100, $this->page->pageVariants[0]->language_id);
    }

    /**
     * This test makes sure that the update() method sets the "published at"
     * to current date & time when page is being published.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testSetsPublishedAt(): void
    {
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'url' => 'somewhere',
                    'status' => PageVariant::STATUS_PUBLISHED,
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Make sure it has been filled the values we provided
        $this->assertNotNull($this->pageVariant->route);
        $this->assertEquals(PageVariant::STATUS_PUBLISHED, $this->pageVariant->status);

        // Make sure update() automatically set the "published at" property
        $this->assertNotNull($this->pageVariant->published_at);
    }

    /**
     * This test makes sure that the update() method creates a new route when
     * told to.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testCreatesRoute(): void
    {
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'url' => 'somewhere',
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Make sure update() created appropriate route
        $this->assertNotNull($this->pageVariant->route);
        $this->assertEquals('somewhere', $this->pageVariant->route->url);
    }

    /**
     * This test makes sure that the update() method updates an already existing
     * route when told to.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testUpdatesRoute(): void
    {
        // Step 1: create a new route (so that we will be able to update it
        // later)
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'url' => 'somewhere',
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Step 2: remove that route
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'url' => 'somewhere-else',
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertNotNull($this->pageVariant->route);
        $this->assertEquals('somewhere-else', $this->pageVariant->route->url);
    }

    /**
     * This test makes sure that the update() method removes an already existing
     * route when told to.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testDeletesRoute(): void
    {
        // Step 1: create a new route (so that we will be able to remove it
        // later)
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'url' => 'somewhere',
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        // Step 2: remove that route
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'url' => '',
                ]
            ],
        ]);

        $this->page = $this->pagesRepository->getById($this->page->id);

        $this->assertNull($this->pageVariant->route);
    }

    /**
     * This test makes sure it is not possible to publish a page that does not
     * have any route.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testForbidsToPublishPageWithoutRoute(): void
    {
        $this->expectExceptionMessage('Published page must have a route.');

        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'status' => PageVariant::STATUS_PUBLISHED,
                ],
            ],
        ]);
    }

    /**
     * This test makes sure it is not possible not publish a post that does not
     * have any route.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testForbidsToPublishPostWithoutLead(): void
    {
        $this->expectExceptionMessage('Published post must contain a lead.');

        // Step 1: create an example post
        $page = $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_BLOG,
            ],

            'pageVariants' => [
                []
            ],
        ]);

        $pageVariant = $page->pageVariants[0];

        // Step 2: try to publish it
        $this->pagesFacade->update($page, [
            'pageVariants' => [
                [
                    'id' => $pageVariant->id,
                    'url' => 'somewhere',
                    'status' => PageVariant::STATUS_PUBLISHED,
                ]
            ],
        ]);
    }

    /**
     * This test makes sure that the update() method correctly adds new tags
     * to an already existing page variant.
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

        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,

                    'tag_ids' => [
                        $tags[0]->id,
                        $tags[1]->id,
                    ],
                ],
            ],
        ]);

        $this->assertCount(2, $this->pageVariant->tags);
        $this->assertEquals($tags[0], $this->pageVariant->tags[0]);
        $this->assertEquals($tags[1], $this->pageVariant->tags[1]);
    }

    /**
     * This test makes sure that the update() method correctly removes existing
     * tags from an already existing page variant.
     *
     * @return void
     *
     * @throws AppException
     */
    public function testRemovesTags(): void
    {
        $this->pagesFacade->update($this->page, [
            'pageVariants' => [
                [
                    'id' => $this->pageVariant->id,
                    'tag_ids' => [],
                ],
            ],
        ]);

        $this->assertCount(0, $this->pageVariant->tags);
    }

    /**
     * This test makes sure that the update() method correctly adds new
     * attachments to an already existing page.
     *
     * @throws AppException
     */
    public function testAddsAttachments(): void
    {
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
     * This test makes sure that the update() method correctly removes existing
     * attachments from an already existing page.
     *
     * @throws AppException
     */
    public function testRemovesAttachments(): void
    {
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
