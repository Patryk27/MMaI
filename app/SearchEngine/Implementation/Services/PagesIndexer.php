<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Models\Page;

class PagesIndexer
{

    /**
     * @var PageVariantsIndexer
     */
    private $pageVariantsIndexer;

    /**
     * @param PageVariantsIndexer $pageVariantsIndexer
     */
    public function __construct(
        PageVariantsIndexer $pageVariantsIndexer
    ) {
        $this->pageVariantsIndexer = $pageVariantsIndexer;
    }

    /**
     * @param Page $page
     * @return void
     */
    public function index(Page $page): void
    {
        foreach ($page->pageVariants as $pageVariant) {
            $this->pageVariantsIndexer->index($pageVariant);
        }
    }

}