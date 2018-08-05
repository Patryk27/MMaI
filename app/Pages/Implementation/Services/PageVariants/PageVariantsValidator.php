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
    }

}