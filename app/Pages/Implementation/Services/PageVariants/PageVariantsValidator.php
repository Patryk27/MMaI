<?php

namespace App\Pages\Implementation\Services\PageVariants;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\PageVariant;

class PageVariantsValidator
{

    /**
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws AppException
     */
    public function validate(PageVariant $pageVariant): void
    {
        $page = $pageVariant->page;

        if (is_null($page)) {
            throw new AppException('Page variant must be associated with a page.');
        }

        if ($pageVariant->isPublished()) {
            if (is_null($pageVariant->route)) {
                throw new AppException('Published page must have a route.');
            }

            if ($page->isBlogPost() && strlen($pageVariant->lead) === 0) {
                throw new AppException('Published post must contain a lead.');
            }
        }

        $this->validateRoute($pageVariant);
        $this->validateTags($pageVariant);
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws AppException
     */
    private function validateRoute(PageVariant $pageVariant): void
    {
        if (isset($pageVariant->route)) {
            if (starts_with($pageVariant->route->url, 'backend/')) {
                throw new AppException('It is not possible to create route in the [backend] namespace.');
            }
        }
    }

    /**
     * @param PageVariant $pageVariant
     * @return void
     *
     * @throws AppException
     */
    private function validateTags(PageVariant $pageVariant): void
    {
        foreach ($pageVariant->tags as $tag) {
            if ($tag->language_id !== $pageVariant->language_id) {
                throw new AppException('Page variant cannot contain tags from other language.');
            }
        }
    }

}