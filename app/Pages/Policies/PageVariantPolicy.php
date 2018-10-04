<?php

namespace App\Pages\Policies;

use App\Pages\Models\PageVariant;
use App\Users\Models\User;

final class PageVariantPolicy
{

    /**
     * @param User|null $user
     * @param PageVariant $pageVariant
     * @return bool
     */
    public function show(?User $user, PageVariant $pageVariant): bool
    {
        return isset($user) || $pageVariant->isPublished();
    }

}
