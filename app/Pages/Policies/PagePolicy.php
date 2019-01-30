<?php

namespace App\Pages\Policies;

use App\Pages\Models\Page;
use App\Users\Models\User;

final class PagePolicy {

    /**
     * @param User|null $user
     * @param Page $page
     * @return bool
     */
    public function show(?User $user, Page $page): bool {
        return isset($user) || $page->isPublished();
    }

    /**
     * @param User|null $user
     * @return bool
     */
    public function edit(?User $user): bool {
        return isset($user);
    }

}
