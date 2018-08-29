<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPageVariantsByIdsQuery;
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
     * @param string $query
     * @return Collection|PageVariant[]
     *
     * @throws PageException
     */
    public function search(string $query): Collection
    {
        $pageVariantIds = $this->getHits($query)->pluck('_id')->all();

        return $this->pagesFacade->queryMany(
            new GetPageVariantsByIdsQuery($pageVariantIds)
        );
    }

    /**
     * @param string $query
     * @return Collection
     */
    private function getHits(string $query): Collection
    {
        $response = $this->elasticsearch->search([
            'body' => [
                'query' => [
                    'simple_query_string' => [
                        'query' => $query,
                        'default_operator' => 'and',
                    ],
                ],
            ],
        ]);

        return new Collection(
            $response['hits']['hits']
        );
    }

}