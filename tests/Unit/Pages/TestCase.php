<?php

namespace Tests\Unit\Pages;

use App\Core\Repositories\InMemoryRepository;
use App\Pages\Implementation\Repositories\InMemoryPagesRepository;
use App\Pages\Implementation\Services\PageVariants\Searcher\InMemoryPageVariantsSearcher;
use App\Pages\PagesFacade;
use App\Pages\PagesFactory;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var InMemoryPagesRepository
     */
    protected $pagesRepository;

    /**
     * @var InMemoryPageVariantsSearcher
     */
    protected $pageVariantsSearcher;

    /**
     * @var PagesFacade
     */
    protected $pagesFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->pagesRepository = new InMemoryPagesRepository(
            new InMemoryRepository()
        );

        $this->pageVariantsSearcher = new InMemoryPageVariantsSearcher();

        $this->pagesFacade = PagesFactory::build($this->pagesRepository, $this->pageVariantsSearcher);
    }

}