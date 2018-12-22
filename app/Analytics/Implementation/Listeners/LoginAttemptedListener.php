<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\Models\Event;
use App\Application\Events\LoginAttempted;

final class LoginAttemptedListener extends Listener
{
    /**
     * @param LoginAttempted $event
     * @return void
     */
    public function handle(LoginAttempted $event): void
    {
        $this->analyticsFacade->create(Event::TYPE_LOGIN_ATTEMPTED, [
            'login' => $event->getLogin(),
            'successful' => $event->isSuccessful(),
        ]);
    }
}
