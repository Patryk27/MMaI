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
        // Query Elasticsearch for ids of page variants matching given text
        // query
        $pageVariantIds = $this->getMatchingPageVariantIds(
            $query->getQuery()
        );

        // Fetch actual page variant models from the database
        $pageVariants = $this->pagesFacade->queryMany(
            new GetPageVariantsByIdsQuery(
                $pageVariantIds->all()
            )
        );

        // Remove all the non-matching page variants; e.g. those with different
        // language id.
        return $this->filterPageVariants($pageVariants, $query);
    }

    /**
     * @param string $query
     * @return Collection|int[]
     */
    private function getMatchingPageVariantIds(string $query): Collection
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

        $hits = new Collection(
            $response['hits']['hits']
        );

        return $hits->pluck('_id');
    }

    /**
     * @param Collection|PageVariant[] $pageVariants
     * @param SearchQuery $query
     * @return Collection|PageVariant[]
     */
    private function filterPageVariants(Collection $pageVariants, SearchQuery $query): Collection
    {
        return $pageVariants->filter(function (PageVariant $pageVariant) use ($query): bool {
            // If query has been set a "language" filter and the current page
            // variant does not match it - filter it out
            if ($query->hasLanguage() && $pageVariant->language_id !== $query->getLanguage()->id) {
                return false;
            }

            // If query has been set a "posts only" filter and the current page
            // variant does not match it - filter it out
            if ($query->isPostsOnly() && !$pageVariant->page->isBlogPost()) {
                return false;
            }

            // Otherwise we're pretty good
            return true;
        });
    }

}