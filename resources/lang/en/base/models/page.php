<?php

use App\Pages\Models\Page;

return [
    'enums' => [
        'type' => [
            Page::TYPE_BLOG => 'Article',
            Page::TYPE_CMS => 'Page',
        ],
    ],
];
