<?php

namespace App\SearchEngine;

use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\SearchEngine\Implementation\Services\Migrator;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use Illuminate\Support\Collection;

class SearchEngineFacade
{

    /**
     * @var Migrator
     */
    private $migrator;

    /**
     * @var PagesIndexer
     */
    private $pagesIndexer;

    /**
     * @param Migrator $migrator
     * @param PagesIndexer $pagesIndexer
     */
    public function __construct(
        Migrator $migrator,
        PagesIndexer $pagesIndexer
    ) {
        $this->migrator = $migrator;
        $this->pagesIndexer = $pagesIndexer;
    }

    /**
     * @param Page $page
     * @return void
     */
    public function index(Page $page): void
    {
        $this->migrator->migrate();
        $this->pagesIndexer->index($page);
    }

    /**
     * @param array $query
     * @return Collection|PageVariant[]
     */
    public function search(array $query): Collection
    {
        $this->migrator->migrate();
    }

}