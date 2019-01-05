<?php

namespace App\Application\Events;

use App\Core\ValueObjects\HasInitializationConstructor;

final class LoginAttempted {
    use HasInitializationConstructor;

    /**
     * Contains the login using which someone was trying to sign in / signed in.
     * @var string
     */
    private $login;

    /**
     * Determines whether the signing-in was successful or not.
     * @var bool
     */
    private $successful;

    /**
     * @return string
     */
    public function getLogin(): string {
        return $this->login;
    }

    /**
     * @return bool
     */
    public function isSuccessful(): bool {
        return $this->successful;
    }
}
