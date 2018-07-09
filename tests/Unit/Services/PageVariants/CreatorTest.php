<?php

namespace Unit\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\Page;
use App\Models\PageVariant;
use App\Services\PageVariants\Creator as PageVariantsCreator;
use App\Services\PageVariants\Validator as PageVariantsValidator;
use Tests\Unit\TestCase;

final class CreatorTest extends TestCase
{

    /**
     * @var PageVariantsCreator
     */
    private $creator;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->creator = new PageVariantsCreator(
            new PageVariantsValidator()
        );
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testFillsBasicProperties(): void
    {
        $page = new Page();

        $pageVariant = $this->creator->create($page, [
            'language_id' => 100,
            'status' => PageVariant::STATUS_DRAFT,
            'title' => 'some title',
            'lead' => 'some lead',
            'content' => 'some content',
        ]);

        $this->assertEquals(100, $pageVariant->language_id);
        $this->assertEquals(PageVariant::STATUS_DRAFT, $pageVariant->status);
        $this->assertEquals('some title', $pageVariant->title);
        $this->assertEquals('some lead', $pageVariant->lead);
        $this->assertEquals('some content', $pageVariant->content);

        $this->assertEquals($page, $pageVariant->page);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testCreatesRoute(): void
    {
        $page = new Page();

        $pageVariant = $this->creator->create($page, [
            'route' => 'somewhere',
        ]);

        $this->assertNotNull($pageVariant->route);
        $this->assertEquals('somewhere', $pageVariant->route->url);
    }

    /**
     * @return void
     *
     * @throws AppException
     */
    public function testCreatesRouteOnlyWhenNecessary(): void
    {
        $page = new Page();
        $pageVariant = $this->creator->create($page, []);

        $this->assertNull($pageVariant->route);
    }

}