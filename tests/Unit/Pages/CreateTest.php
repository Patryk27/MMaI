<?php

namespace Tests\Unit\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;

class CreateTest extends TestCase
{

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testCreateFillsBasicProperties(): void
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

        $page = $this->pagesRepository->getById($page->id);
        $pageVariant = $page->pageVariants->first();

        $this->assertEquals($page->type, Page::TYPE_CMS);

        $this->assertEquals(PageVariant::STATUS_DRAFT, $pageVariant->status);
        $this->assertEquals('some title', $pageVariant->title);
        $this->assertEquals('some lead', $pageVariant->lead);
        $this->assertEquals('some content', $pageVariant->content);
        $this->assertNull($pageVariant->route);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testCreateCreatesRoute(): void
    {
        $page = $this->pagesFacade->create([
            'page' => [
                'type' => Page::TYPE_CMS,
            ],

            'pageVariants' => [
                [
                    'route' => '/somewhere',
                ],
            ],
        ]);

        $page = $this->pagesRepository->getById($page->id);
        $pageVariant = $page->pageVariants->first();

        $this->assertNotNull($pageVariant->route);
        $this->assertEquals('/somewhere', $pageVariant->route->url);
    }

}