<?php

namespace App\Pages\Policies;

use App\Users\Models\User;

final class PagePolicy
{

    /**
     * @param User|null $user
     * @return bool
     */
    public function edit(?User $user): bool
    {
        return isset($user);
    }

}
