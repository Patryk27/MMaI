<?php

namespace App\Pages;

use App\Pages\Internal\Services\Pages\PagesCreator;
use App\Pages\Internal\Services\Pages\PagesUpdater;
use App\Pages\Models\Page;

class PagesFacade
{

    /**
     * @var PagesCreator
     */
    private $pagesCreator;

    /**
     * @var PagesUpdater
     */
    private $pagesUpdater;

    /**
     * @param PagesCreator $pagesCreator
     * @param PagesUpdater $pagesUpdater
     */
    public function __construct(
        PagesCreator $pagesCreator,
        PagesUpdater $pagesUpdater
    ) {
        $this->pagesCreator = $pagesCreator;
        $this->pagesUpdater = $pagesUpdater;
    }

    /**
     * @param array $pageData
     * @return Page
     */
    public function create(array $pageData): Page
    {
        return $this->pagesCreator->create($pageData);
    }

    /**
     * @param Page $page
     * @param array $pageData
     * @return void
     */
    public function update(Page $page, array $pageData): void
    {
        $this->pagesUpdater->update($page, $pageData);
    }

}