<?php

namespace App\Search\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPagesByIds;
use App\Search\Events\QueryPerformed;
use App\Search\Queries\Search;
use Elasticsearch\Client as ElasticsearchClient;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Support\Collection;

class PagesSearcher {

    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var ElasticsearchClient */
    private $elasticsearch;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        EventsDispatcher $eventsDispatcher,
        ElasticsearchClient $elasticsearch,
        PagesFacade $pagesFacade
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->elasticsearch = $elasticsearch;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @param Search $query
     * @return Collection|Page[]
     * @throws PageException
     */
    public function search(Search $query): Collection {
        $pageIds = $this->getMatchingPageIds($query);

        $this->eventsDispatcher->dispatch(
            new QueryPerformed($query, $pageIds->all())
        );

        return $this->pagesFacade->queryMany(
            new GetPagesByIds(
                $pageIds->all()
            )
        );
    }

    /**
     * @param Search $query
     * @return Collection|int[]
     */
    private function getMatchingPageIds(Search $query): Collection {
        $filters = [];

        if ($query->hasType()) {
            $filters[] = [
                'term' => [
                    'page_type' => $query->getType(),
                ],
            ];
        }

        if ($query->hasWebsite()) {
            $filters[] = [
                'term' => [
                    'website_id' => $query->getWebsite()->id,
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
