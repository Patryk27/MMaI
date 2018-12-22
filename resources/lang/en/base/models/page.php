<?php

use App\Pages\Models\Page;

return [
    'fields' => [
        'language_id' => 'Language',
        'title' => 'Title',
        'lead ' => 'Lead',
        'content' => 'Content',
        'notes' => 'Notes',
        'type' => 'Type',
        'status' => 'Status',
        'created_at' => 'Created at',
        'updated_at' => 'Updated at',
        'published_at' => 'Published at',

        // -- relationships -- //

        'language' => 'Language',
        'route' => 'Route',
        'tags' => 'Tags',
    ],

    'enums' => [
        'type' => [
            Page::TYPE_PAGE => 'Page',
            Page::TYPE_POST => 'Post',
        ],

        'status' => [
            Page::STATUS_DRAFT => 'Draft',
            Page::STATUS_PUBLISHED => 'Published',
            Page::STATUS_DELETED => 'Deleted',
        ],
    ],
];
