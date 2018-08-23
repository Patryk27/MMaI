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
     * @return void
     *
     * @throws AppException
     */
    public function testFillsBasicProperties(): void
    {
        $page = $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_CMS,
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

        $pageVariant = $page->pageVariants[0];

        // Execute the assertions
        $this->assertEquals(Page::TYPE_CMS, $page->type);

        $this->assertEquals(100, $pageVariant->language_id);
        $this->assertEquals(PageVariant::STATUS_DRAFT, $pageVariant->status);
        $this->assertEquals('some title', $pageVariant->title);
        $this->assertEquals('some lead', $pageVariant->lead);
        $this->assertEquals('some content', $pageVariant->content);
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

        $pageVariant = $page->pageVariants->first();

        // Execute the assertions
        $this->assertNotNull($pageVariant);

        $this->assertCount(2, $pageVariant->tags);
        $this->assertEquals($tags[0]->id, $pageVariant->tags[0]->id);
        $this->assertEquals($tags[0]->id, $pageVariant->tags[0]->id);
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
    public function testForbidsAddTagsFromOtherLanguages(): void
    {
        $this->expectExceptionMessage('Page variant cannot contain tags from other language.');

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

}