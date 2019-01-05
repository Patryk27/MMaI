<?php

namespace App\SearchEngine;

use App\Pages\Events\PageCreated;
use App\Pages\Events\PageUpdated;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\SearchEngine\Implementation\Listeners\PageCreatedListener;
use App\SearchEngine\Implementation\Listeners\PageUpdatedListener;
use App\SearchEngine\Implementation\Listeners\TagUpdatedListener;
use App\SearchEngine\Implementation\Services\ElasticsearchMigrator;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use App\SearchEngine\Implementation\Services\PagesSearcher;
use App\SearchEngine\Queries\SearchQuery;
use App\Tags\Events\TagUpdated;
use Event;
use Illuminate\Support\Collection;

final class SearchEngineFacade {
    /** @var ElasticsearchMigrator */
    private $elasticsearchMigrator;

    /** @var PagesIndexer */
    private $pagesIndexer;

    /** @var PagesSearcher */
    private $pagesSearcher;

    public function __construct(
        ElasticsearchMigrator $elasticsearchMigrator,
        PagesIndexer $pagesIndexer,
        PagesSearcher $pagesSearcher
    ) {
        $this->elasticsearchMigrator = $elasticsearchMigrator;
        $this->pagesIndexer = $pagesIndexer;
        $this->pagesSearcher = $pagesSearcher;
    }

    /**
     * @return void
     */
    public function boot(): void {
        Event::listen(PageCreated::class, PageCreatedListener::class);
        Event::listen(PageUpdated::class, PageUpdatedListener::class);
        Event::listen(TagUpdated::class, TagUpdatedListener::class);
    }

    /**
     * @param Page $page
     * @return void
     */
    public function index(Page $page): void {
        $this->elasticsearchMigrator->migrate();
        $this->pagesIndexer->index($page);
    }

    /**
     * @param SearchQuery $query
     * @return Collection|Page[]
     * @throws PageException
     */
    public function search(SearchQuery $query): Collection {
        $this->elasticsearchMigrator->migrate();

        return $this->pagesSearcher->search($query);
    }
}
