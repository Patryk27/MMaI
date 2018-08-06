<?php

use App\Pages\Models\PageVariant;

return [
    'fields' => [
        'page_id' => 'Page',
        'language_id' => 'Language',
        'status' => 'Status',
        'title' => 'Title',
        'lead '=> 'Lead',
        'content' => 'Content',
        'published_at' => 'Published at',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',

        // -- relationships -- //

        'page' => 'Page',
        'language' => 'Language',
        'route' => 'Route',
        'tags' => 'Tags',
    ],

    'enums' => [
        'status' => [
            PageVariant::STATUS_DRAFT => 'Draft',
            PageVariant::STATUS_PUBLISHED => 'Published',
            PageVariant::STATUS_DELETED => 'Deleted',
        ],
    ],
];