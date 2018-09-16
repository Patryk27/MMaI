<?php

namespace App\SearchEngine\Implementation\Services;

use App\Pages\Models\PageVariant;
use App\SearchEngine\Implementation\Policies\PageVariantsIndexerPolicy;
use Elasticsearch\Client as ElasticsearchClient;
use Elasticsearch\Common\Exceptions\Missing404Exception as ElasticsearchMissing404Exception;

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
     * @param ElasticsearchClient $elasticsearch
     * @param PageVariantsIndexerPolicy $pageVariantsIndexerPolicy
     */
    public function __construct(
        ElasticsearchClient $elasticsearch,
        PageVariantsIndexerPolicy $pageVariantsIndexerPolicy
    ) {
        $this->elasticsearch = $elasticsearch;
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
                // Include information about PV
                'id' => $pageVariant->id,
                'title' => $pageVariant->title,
                'lead' => $pageVariant->lead,
                'content' => $pageVariant->content,
                'created_at' => $pageVariant->created_at->format('Y-m-d'),

                // Include information about PV's page
                'page_id' => $pageVariant->page->id,
                'page_type' => $pageVariant->page->type,

                // Include information about PV's language
                'language_id' => $pageVariant->language_id,
                'language' => [
                    $pageVariant->language->native_name,
                    $pageVariant->language->english_name,
                ],

                // Include information about PV's tags
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
        try {
            $this->elasticsearch->delete([
                'index' => 'pages',
                'type' => 'page_variants',
                'id' => $pageVariant->id,
            ]);
        } catch (ElasticsearchMissing404Exception $ex) {
            // nottin' here
        }
    }

}
