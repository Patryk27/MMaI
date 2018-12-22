<?php

namespace App\Analytics\Implementation\Repositories;

use App\Analytics\Models\Event;

interface EventsRepository
{
    /**
     * Saves given event to the database.
     *
     * @param Event $event
     * @return void
     */
    public function persist(Event $event): void;
}
