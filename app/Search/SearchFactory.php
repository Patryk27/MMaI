<?php

namespace App\Search;

use App\Pages\PagesFacade;
use App\Search\Implementation\Policies\PagesIndexerPolicy;
use App\Search\Implementation\Services\ElasticsearchMigrator;
use App\Search\Implementation\Services\PagesIndexer;
use App\Search\Implementation\Services\PagesSearcher;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

final class SearchFactory {

    public static function build(
        EventsDispatcher $eventsDispatcher,
        ElasticsearchClient $elasticsearch,
        PagesFacade $pagesFacade
    ): SearchFacade {
        $elasticsearchMigrator = new ElasticsearchMigrator($elasticsearch);

        $pagesIndexerPolicy = new PagesIndexerPolicy();
        $pagesIndexer = new PagesIndexer($elasticsearch, $pagesIndexerPolicy);

        $pagesSearcher = new PagesSearcher($eventsDispatcher, $elasticsearch, $pagesFacade);

        return new SearchFacade(
            $elasticsearchMigrator,
            $pagesIndexer,
            $pagesSearcher
        );
    }

}
