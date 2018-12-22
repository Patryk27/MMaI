<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPagesByIdsQuery;
use App\SearchEngine\Events\QuerySearched;
use App\SearchEngine\Queries\SearchQuery;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;
use Illuminate\Support\Collection;

class PagesSearcher
{
    /** @var EventsDispatcherContract */
    private $eventsDispatcher;

    /** @var ElasticsearchClient */
    private $elasticsearch;

    /** @var PagesFacade */
    private $pagesFacade;

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
     * @return Collection|Page[]
     * @throws PageException
     */
    public function search(SearchQuery $query): Collection
    {
        $pageIds = $this->getMatchingPageIds($query);

        $this->eventsDispatcher->dispatch(
            new QuerySearched($query, $pageIds->all())
        );

        return $this->pagesFacade->queryMany(
            new GetPagesByIdsQuery(
                $pageIds->all()
            )
        );
    }

    /**
     * @param SearchQuery $query
     * @return Collection|int[]
     */
    private function getMatchingPageIds(SearchQuery $query): Collection
    {
        $filters = [];

        if ($query->hasType()) {
            $filters[] = [
                'term' => [
                    'page_type' => $query->getType(),
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
