<?php

namespace App\SearchEngine;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\SearchEngine\Implementation\Listeners\PagesListener;
use App\SearchEngine\Implementation\Services\ElasticsearchMigrator;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use App\SearchEngine\Implementation\Services\PageVariantsSearcher;
use App\SearchEngine\Queries\SearchQuery;
use Illuminate\Support\Collection;

final class SearchEngineFacade
{

    /**
     * @var PagesListener
     */
    private $pagesListener;

    /**
     * @var ElasticsearchMigrator
     */
    private $elasticsearchMigrator;

    /**
     * @var PagesIndexer
     */
    private $pagesIndexer;

    /**
     * @var PageVariantsSearcher
     */
    private $pageVariantsSearcher;

    /**
     * @param PagesListener $pagesListener
     * @param ElasticsearchMigrator $elasticsearchMigrator
     * @param PagesIndexer $pagesIndexer
     * @param PageVariantsSearcher $pageVariantsSearcher
     */
    public function __construct(
        PagesListener $pagesListener,
        ElasticsearchMigrator $elasticsearchMigrator,
        PagesIndexer $pagesIndexer,
        PageVariantsSearcher $pageVariantsSearcher
    ) {
        $this->pagesListener = $pagesListener;
        $this->pagesListener->initialize();

        $this->elasticsearchMigrator = $elasticsearchMigrator;
        $this->pagesIndexer = $pagesIndexer;
        $this->pageVariantsSearcher = $pageVariantsSearcher;
    }

    /**
     * @param Page $page
     * @return void
     */
    public function index(Page $page): void
    {
        $this->elasticsearchMigrator->migrate();
        $this->pagesIndexer->index($page);
    }

    /**
     * @param SearchQuery $query
     * @return Collection|PageVariant[]
     *
     * @throws PageException
     */
    public function search(SearchQuery $query): Collection
    {
        $this->elasticsearchMigrator->migrate();

        return $this->pageVariantsSearcher->search($query);
    }

}