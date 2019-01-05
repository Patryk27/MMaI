<?php

namespace App\Pages\Implementation\Services;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;

class PagesValidator {
    /**
     * @param Page $page
     * @return void
     * @throws PageException
     */
    public function validate(Page $page): void {
        unimplemented();
    }

    /**
     * @param Page $page
     * @return void
     * @throws PageException
     */
    private function validateTags(Page $page): void {
        foreach ($page->tags as $tag) {
            if ($tag->website_id !== $page->website) {
                throw new PageException('Page cannot contain tags from other websites.');
            }
        }
    }
}
