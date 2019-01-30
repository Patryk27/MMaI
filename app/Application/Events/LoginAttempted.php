<?php

namespace App\Application\Events;

use App\Core\ValueObjects\HasInitializationConstructor;

final class LoginAttempted {

    use HasInitializationConstructor;

    /** @var string */
    private $login;

    /** @var bool */
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
