<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Models\PageVariant;
use Cviebrock\LaravelElasticsearch\Manager as ElasticsearchManager;
use Elasticsearch\Client as ElasticsearchClient;

class PageVariantsIndexer
{

    /**
     * @var ElasticsearchClient
     */
    private $elasticsearch;

    /**
     * @param ElasticsearchManager $elasticsearchManager
     */
    public function __construct(
        ElasticsearchManager $elasticsearchManager
    ) {
        $this->elasticsearch = $elasticsearchManager->connection();
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     */
    public function index(PageVariant $pageVariant): void
    {
        // @todo do not include in the index if PV is not published
        // @todo remove from index when PV is not published

        $this->elasticsearch->index([
            'index' => 'pages',
            'type' => 'page_variants',
            'id' => $pageVariant->id,

            'body' => [
                'status' => $pageVariant->status,
                'title' => $pageVariant->title,
                'lead' => $pageVariant->lead,
                'content' => $pageVariant->content,
                'created_at' => $pageVariant->created_at->format('Y-m-d'),

                'language' => [
                    $pageVariant->language->native_name,
                    $pageVariant->language->english_name,
                ],

                'tags' => $pageVariant->tags->pluck('name')->unique(),
            ],
        ]);
    }

}