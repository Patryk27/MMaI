<?php

namespace App\Pages\Implementation\Services\Pages;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;

class PagesValidator
{

    /**
     * @param Page $page
     * @return void
     *
     * @throws PageException
     */
    public function validate(Page $page): void
    {
        if ($page->pageVariants->count() === 0) {
            throw new PageException('Each page must contain at least one variant.');
        }
    }

}
