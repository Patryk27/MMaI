<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPageVariantsByIdsQuery;
use App\SearchEngine\Queries\SearchQuery;
use Cviebrock\LaravelElasticsearch\Manager as ElasticsearchManager;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Support\Collection;

class PageVariantsSearcher
{

    /**
     * @var ElasticsearchClient
     */
    private $elasticsearch;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param ElasticsearchManager $elasticsearchManager
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        ElasticsearchManager $elasticsearchManager,
        PagesFacade $pagesFacade
    ) {
        $this->elasticsearch = $elasticsearchManager->connection();
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @param SearchQuery $query
     * @return Collection|PageVariant[]
     *
     * @throws PageException
     */
    public function search(SearchQuery $query): Collection
    {
        // Step 1: Retrieve ids of matching page variants from the Elasticsearch
        $pageVariantIds = $this->getMatchingPageVariantIds($query);

        // Step 2: Retrieve actual page variant models from the MySQL
        return $this->pagesFacade->queryMany(
            new GetPageVariantsByIdsQuery(
                $pageVariantIds->all()
            )
        );
    }

    /**
     * @param SearchQuery $query
     * @return Collection|int[]
     */
    private function getMatchingPageVariantIds(SearchQuery $query): Collection
    {
        $filters = [];

        if ($query->hasPageType()) {
            $filters[] = [
                'term' => [
                    'page_type' => $query->getPageType(),
                ],
            ];
        }

        if ($query->hasLanguage()) {
            $filters[] = [
                'term' => [
                    'language_id' => $query->getLanguage()->id,
                ],
            ];
        }

        $response = $this->elasticsearch->search([
            'explain' => true,

            'body' => [
                'size' => 100,

                'query' => [
                    'bool' => [
                        'must' => [
                            'simple_query_string' => [
                                'query' => $query->getQuery(),
                                'default_operator' => 'and',
                            ],
                        ],

                        'filter' => $filters,
                    ],
                ],
            ],
        ]);

        $hits = new Collection(
            $response['hits']['hits']
        );

        return $hits->pluck('_id');
    }

}