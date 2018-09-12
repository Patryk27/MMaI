<?php

namespace App\SearchEngine;

use App\Pages\Events\PageCreated;
use App\Pages\Events\PageUpdated;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\SearchEngine\Implementation\Listeners\PageCreatedListener;
use App\SearchEngine\Implementation\Listeners\PageUpdatedListener;
use App\SearchEngine\Implementation\Listeners\TagUpdatedListener;
use App\SearchEngine\Implementation\Services\ElasticsearchMigrator;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use App\SearchEngine\Implementation\Services\PageVariantsSearcher;
use App\SearchEngine\Queries\SearchQuery;
use App\Tags\Events\TagUpdated;
use Illuminate\Support\Collection;

final class SearchEngineFacade
{

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
     * @param ElasticsearchMigrator $elasticsearchMigrator
     * @param PagesIndexer $pagesIndexer
     * @param PageVariantsSearcher $pageVariantsSearcher
     */
    public function __construct(
        ElasticsearchMigrator $elasticsearchMigrator,
        PagesIndexer $pagesIndexer,
        PageVariantsSearcher $pageVariantsSearcher
    ) {
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

    /**
     * @return array
     */
    public static function getListeners(): array
    {
        return [
            PageCreated::class => PageCreatedListener::class,
            PageUpdated::class => PageUpdatedListener::class,

            TagUpdated::class => TagUpdatedListener::class,
            // @todo TagDeleted::class => TagDeletedListener::class,
        ];
    }

}
