<?php

namespace Tests\Unit\Pages;

use App\Pages\PagesFacade;
use App\Pages\PagesFactory;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var PagesFacade
     */
    protected $pagesFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->pagesFacade = PagesFactory::build();
    }

}