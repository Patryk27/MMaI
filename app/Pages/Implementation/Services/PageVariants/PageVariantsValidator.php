<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Pages\Exceptions\PageException;
use App\Pages\Models\PageVariant;

class PageVariantsValidator
{

    /**
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws PageException
     */
    public function validate(PageVariant $pageVariant): void
    {
        $page = $pageVariant->page;

        if ($pageVariant->isPublished()) {
            if (is_null($pageVariant->route)) {
                throw new PageException('Published page must have a route.');
            }

            if ($page->isBlogPost() && strlen($pageVariant->lead) === 0) {
                throw new PageException('Published post must contain a lead.');
            }
        }

        $this->validateTags($pageVariant);
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws PageException
     */
    private function validateTags(PageVariant $pageVariant): void
    {
        foreach ($pageVariant->tags as $tag) {
            if ($tag->language_id !== $pageVariant->language_id) {
                throw new PageException('Page variant cannot contain tags from other languages.');
            }
        }
    }

}
