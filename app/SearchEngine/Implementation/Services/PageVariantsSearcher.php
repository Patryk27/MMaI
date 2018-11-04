<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPageVariantsByIdsQuery;
use App\SearchEngine\Events\QuerySearched;
use App\SearchEngine\Queries\SearchQuery;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;
use Illuminate\Support\Collection;

class PageVariantsSearcher
{

    /**
     * @var EventsDispatcherContract
     */
    private $eventsDispatcher;

    /**
     * @var ElasticsearchClient
     */
    private $elasticsearch;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param EventsDispatcherContract $eventsDispatcher
     * @param ElasticsearchClient $elasticsearch
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        ElasticsearchClient $elasticsearch,
        PagesFacade $pagesFacade
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->elasticsearch = $elasticsearch;
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

        // Step 2: Emit the "query searched" event
        $this->eventsDispatcher->dispatch(
            new QuerySearched($query, $pageVariantIds->all())
        );

        // Step 3: Retrieve actual page variant models from the MySQL
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
