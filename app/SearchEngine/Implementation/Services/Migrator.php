<?php

namespace App\SearchEngine\Implementation\Services;

use Cviebrock\LaravelElasticsearch\Manager as ElasticsearchManager;
use Elasticsearch\Client as ElasticsearchClient;

class Migrator
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
     * @return void
     */
    public function migrate(): void
    {
        if (!$this->doesIndexExist('pages')) {
            $this->createPagesIndex();
        }
    }

    /**
     * @param string $index
     * @return bool
     */
    private function doesIndexExist(string $index): bool
    {
        return $this->elasticsearch->indices()->exists([
            'index' => $index,
        ]);
    }

    /**
     * @return void
     */
    private function createPagesIndex(): void
    {
        $this->elasticsearch->indices()->create([
            'index' => 'pages',

            'body' => [
                'mappings' => [
                    'page_variants' => [
                        'properties' => [
                            'status' => [
                                'type' => 'keyword',
                            ],

                            'title' => [
                                'type' => 'text',
                                'analyzer' => 'autocomplete',
                            ],

                            'lead' => [
                                'type' => 'text',
                                'analyzer' => 'autocomplete',
                            ],

                            'content' => [
                                'type' => 'text',
                                'analyzer' => 'autocomplete',
                            ],

                            'created_at' => [
                                'type' => 'keyword',
                            ],

                            'language' => [
                                'type' => 'keyword',
                            ],

                            'tags' => [
                                'type' => 'keyword',
                            ],
                        ],
                    ],
                ],

                'settings' => [
                    'analysis' => [
                        'filter' => [
                            'autocomplete_filter' => [
                                'type' => 'edge_ngram',
                                'min_gram' => 1,
                                'max_gram' => 20,
                            ],
                        ],

                        'analyzer' => [
                            'autocomplete' => [
                                'type' => 'custom',
                                'tokenizer' => 'standard',

                                'filter' => [
                                    'lowercase',
                                    'autocomplete_filter',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

}