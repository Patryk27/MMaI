<?php

namespace App\Services\PageVariants;

use App\Exceptions\Exception as AppException;
use App\Models\PageVariant;

class Validator
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

            if ($page->isBlogPage() && strlen($pageVariant->lead) === 0) {
                throw new AppException('Published post must contain a lead.');
            }
        }
    }

}