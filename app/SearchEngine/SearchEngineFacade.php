<?php

namespace App\SearchEngine;

use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\SearchEngine\Implementation\Listeners\PagesListener;
use App\SearchEngine\Implementation\Services\Migrator;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use Illuminate\Support\Collection;

final class SearchEngineFacade
{

    /**
     * @var PagesListener
     */
    private $pagesListener;

    /**
     * @var Migrator
     */
    private $migrator;

    /**
     * @var PagesIndexer
     */
    private $pagesIndexer;

    /**
     * @param PagesListener $pagesListener
     * @param Migrator $migrator
     * @param PagesIndexer $pagesIndexer
     */
    public function __construct(
        PagesListener $pagesListener,
        Migrator $migrator,
        PagesIndexer $pagesIndexer
    ) {
        $this->pagesListener = $pagesListener;
        $this->pagesListener->boot();

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
        unimplemented();
    }

}