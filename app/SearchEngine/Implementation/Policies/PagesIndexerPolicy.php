<?php

namespace App\SearchEngine\Implementation\Policies;

use App\Pages\Models\Page;

final class PagesIndexerPolicy {

    /**
     * @param Page $page
     * @return bool
     */
    public function canBeIndexed(Page $page): bool {
        return $page->isPublished();
    }

}
