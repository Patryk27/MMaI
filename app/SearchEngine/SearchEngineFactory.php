<?php

namespace App\SearchEngine;

use App\Pages\PagesFacade;
use App\SearchEngine\Implementation\Policies\PageVariantsIndexerPolicy;
use App\SearchEngine\Implementation\Services\ElasticsearchMigrator;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use App\SearchEngine\Implementation\Services\PageVariantsIndexer;
use App\SearchEngine\Implementation\Services\PageVariantsSearcher;
use Elasticsearch\Client as ElasticsearchClient;

final class SearchEngineFactory
{

    /**
     * Builds an instance of @see SearchEngineFacade.
     *
     * @param ElasticsearchClient $elasticsearch
     * @param PagesFacade $pagesFacade
     * @return SearchEngineFacade
     */
    public static function build(
        ElasticsearchClient $elasticsearch,
        PagesFacade $pagesFacade
    ): SearchEngineFacade {
        $elasticsearchMigrator = new ElasticsearchMigrator($elasticsearch);

        $pageVariantsIndexerPolicy = new PageVariantsIndexerPolicy();
        $pageVariantsIndexer = new PageVariantsIndexer($elasticsearch, $pageVariantsIndexerPolicy);
        $pagesIndexer = new PagesIndexer($pageVariantsIndexer);

        $pageVariantsSearcher = new PageVariantsSearcher($elasticsearch, $pagesFacade);

        return new SearchEngineFacade(
            $elasticsearchMigrator,
            $pagesIndexer,
            $pageVariantsSearcher
        );
    }

}
