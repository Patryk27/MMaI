<?php

namespace App\Pages\Implementation\Services\Grid;

use App\Grid\Schema\GridSchema;
use App\Grid\Schema\GridSchemaField;
use App\Grid\Schema\GridSchemaFieldType;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Queries\GetAllWebsites;
use App\Websites\WebsitesFacade;

class PagesGridSchemaProvider {

    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(WebsitesFacade $websitesFacade) {
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @return GridSchema
     * @throws WebsiteException
     */
    public function prepareSchema(): GridSchema {
        return new GridSchema([
            'fields' => [
                new GridSchemaField([
                    'id' => 'id',
                    'name' => 'Id',
                    'type' => GridSchemaFieldType::integer(),
                    'sortable' => true,
                ]),

                new GridSchemaField([
                    'id' => 'website',
                    'name' => 'Website',
                    'type' => GridSchemaFieldType::association(),
                    'values' => $this->getWebsites(),
                    'sortable' => true,
                ]),

                new GridSchemaField([
                    'id' => 'type',
                    'name' => 'Type',
                    'type' => GridSchemaFieldType::enum(),
                    'values' => $this->getPageTypes(),
                ]),

                new GridSchemaField([
                    'id' => 'title',
                    'name' => 'Title',
                    'type' => GridSchemaFieldType::string(),
                    'sortable' => true,
                ]),

                new GridSchemaField([
                    'id' => 'status',
                    'name' => 'Status',
                    'type' => GridSchemaFieldType::enum(),
                    'values' => $this->getStatuses(),
                ]),

                new GridSchemaField([
                    'id' => 'createdAt',
                    'name' => 'Created at',
                    'type' => GridSchemaFieldType::dateTime(),
                ]),
            ],
        ]);
    }

    /**
     * @return array
     * @throws WebsiteException
     */
    private function getWebsites(): array {
        return $this->websitesFacade->queryMany(new GetAllWebsites())
            ->pluck('name', 'id')
            ->all();
    }

    /**
     * @return array
     */
    private function getPageTypes(): array {
        return []; // @todo
    }

    /**
     * @return array
     */
    private function getStatuses(): array {
        return []; // @todo
    }

}
