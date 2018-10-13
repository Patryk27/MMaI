<?php

use App\Pages\Models\Page;

return [
    'enums' => [
        'type' => [
            Page::TYPE_CMS => 'Page',
            Page::TYPE_BLOG => 'Post',
        ],
    ],
];
