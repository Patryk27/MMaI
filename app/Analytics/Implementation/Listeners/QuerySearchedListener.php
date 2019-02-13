<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\Models\Event;
use App\Search\Events\QueryPerformed;

final class QuerySearchedListener extends Listener {

    /**
     * @param QueryPerformed $event
     * @return void
     */
    public function handle(QueryPerformed $event): void {
        $query = $event->getQuery();

        $this->analyticsFacade->create(Event::TYPE_QUERY_SEARCHED, [
            'query' => [
                'query' => $query->getQuery(),
                'languageId' => $query->getWebsite() ? $query->getWebsite()->id : null,
            ],

            'matchedIds' => $event->getMatchedIds(),
            'numberOfMatchedIds' => count($event->getMatchedIds()),
        ]);
    }

}
