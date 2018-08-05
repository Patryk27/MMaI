<?php

namespace Tests\Unit\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;

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

        // Create an example page variant
        $this->pageVariant = new PageVariant([
            'language_id' => 100,
            'status' => PageVariant::STATUS_DRAFT,
            'title' => 'some title',
            'lead' => 'some lead',
            'content' => 'some content',
        ]);

        // Create an example page and bind that page variant to it
        $this->page = new Page([
            'type' => Page::TYPE_CMS,
        ]);

        $this->page->pageVariants->push($this->pageVariant);

        // Save the example page so that we can update it in a second
        $this->pagesRepository->persist($this->page);
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

        $this->assertCount(2, $this->page->pageVariants);

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

        $this->assertCount(1, $this->page->pageVariants);

        $pageVariant = $this->page->pageVariants[0];

        $this->assertEquals(100, $pageVariant->language_id);
        $this->assertEquals(PageVariant::STATUS_DRAFT, $pageVariant->status);
        $this->assertEquals('some title', $pageVariant->title);
        $this->assertEquals('some updated lead', $pageVariant->lead);
        $this->assertEquals('some content', $pageVariant->content);
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

        $this->assertCount(1, $this->page->pageVariants);
        $this->assertEquals(100, $this->page->pageVariants[0]->language_id);
    }

    /**
     * This test makes sure that the update() method @todo
     *
     * @return void
     *
     * @throws AppException
     */
    public function testSetsPublishedAt(): void
    {

    }

    public function testCreatesRoute(): void
    {

    }

    public function testUpdatesRoute(): void
    {

    }

    public function testDeletesRoute(): void
    {

    }

    public function testForbidsToPublishPageWithoutRoute(): void
    {

    }

    public function testForbidsToPublishPostWithoutLead(): void
    {
        
    }

}