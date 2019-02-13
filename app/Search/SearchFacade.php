<?php

namespace App\Search;

use App\Pages\Events\PageCreated;
use App\Pages\Events\PageUpdated;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Search\Implementation\Listeners\PageCreatedListener;
use App\Search\Implementation\Listeners\PageUpdatedListener;
use App\Search\Implementation\Listeners\TagUpdatedListener;
use App\Search\Implementation\Services\ElasticsearchMigrator;
use App\Search\Implementation\Services\PagesIndexer;
use App\Search\Implementation\Services\PagesSearcher;
use App\Search\Queries\Search;
use App\Tags\Events\TagUpdated;
use Event;
use Illuminate\Support\Collection;

final class SearchFacade {

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
     * @param Search $query
     * @return Collection|Page[]
     * @throws PageException
     */
    public function search(Search $query): Collection {
        $this->elasticsearchMigrator->migrate();

        return $this->pagesSearcher->search($query);
    }

}
