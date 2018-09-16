<?php

namespace App\SearchEngine\Implementation\Services;

use Elasticsearch\Client as ElasticsearchClient;

class ElasticsearchMigrator
{

    /**
     * @var ElasticsearchClient
     */
    private $elasticsearch;

    /**
     * @param ElasticsearchClient $elasticsearch
     */
    public function __construct(
        ElasticsearchClient $elasticsearch
    ) {
        $this->elasticsearch = $elasticsearch;
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
                            'id' => [
                                'type' => 'integer',
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

                            'page_id' => [
                                'type' => 'integer',
                            ],

                            'page_type' => [
                                'type' => 'keyword',
                            ],

                            'language_id' => [
                                'type' => 'integer',
                            ],

                            'language' => [
                                'type' => 'keyword',
                            ],

                            'tag_ids' => [
                                'type' => 'integer',
                            ],

                            'tags' => [
                                'type' => 'keyword',
                            ],
                        ],
                    ],
                ],

                'settings' => [
                    'number_of_shards' => 1,

                    'analysis' => [
                        'filter' => [
                            'autocomplete_filter' => [
                                'type' => 'edge_ngram',
                                'min_gram' => 2,
                                'max_gram' => 10,
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
