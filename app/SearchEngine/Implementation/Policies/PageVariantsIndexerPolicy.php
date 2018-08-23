<?php

namespace App\SearchEngine\Implementation\Policies;

use App\Pages\Models\PageVariant;

class PageVariantsIndexerPolicy
{

    /**
     * @param PageVariant $pageVariant
     * @return bool
     */
    public function canBeIndexed(PageVariant $pageVariant): bool
    {
        return $pageVariant->isPublished();
    }

}