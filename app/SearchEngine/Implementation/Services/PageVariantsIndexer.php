<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Models\PageVariant;
use App\SearchEngine\Implementation\Policies\PageVariantsIndexerPolicy;
use Cviebrock\LaravelElasticsearch\Manager as ElasticsearchManager;
use Elasticsearch\Client as ElasticsearchClient;

class PageVariantsIndexer
{

    /**
     * @var ElasticsearchClient
     */
    private $elasticsearch;

    /**
     * @var PageVariantsIndexerPolicy
     */
    private $pageVariantsIndexerPolicy;

    /**
     * @param ElasticsearchManager $elasticsearchManager
     * @param PageVariantsIndexerPolicy $pageVariantsIndexerPolicy
     */
    public function __construct(
        ElasticsearchManager $elasticsearchManager,
        PageVariantsIndexerPolicy $pageVariantsIndexerPolicy
    ) {
        $this->elasticsearch = $elasticsearchManager->connection();
        $this->pageVariantsIndexerPolicy = $pageVariantsIndexerPolicy;
    }

    /**
     * @param PageVariant $pageVariant
     * @return bool
     */
    public function index(PageVariant $pageVariant): bool
    {
        if ($this->pageVariantsIndexerPolicy->canBeIndexed($pageVariant)) {
            $this->createOrUpdateIndex($pageVariant);

            return true;
        } else {
            $this->deleteIndex($pageVariant);

            return false;
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     */
    private function createOrUpdateIndex(PageVariant $pageVariant): void
    {
        $this->elasticsearch->index([
            'index' => 'pages',
            'type' => 'page_variants',
            'id' => $pageVariant->id,

            'body' => [
                'title' => $pageVariant->title,
                'lead' => $pageVariant->lead,
                'content' => $pageVariant->content,
                'created_at' => $pageVariant->created_at->format('Y-m-d'),

                'language_id' => $pageVariant->language_id,

                'language' => [
                    $pageVariant->language->native_name,
                    $pageVariant->language->english_name,
                ],

                'tag_ids' => $pageVariant->tags->pluck('id'),

                'tags' => $pageVariant->tags->pluck('name'),
            ],
        ]);
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     */
    private function deleteIndex(PageVariant $pageVariant): void
    {
        $this->elasticsearch->delete([
            'index' => 'pages',
            'type' => 'page_variants',
            'id' => $pageVariant->id,
        ]);
    }

}