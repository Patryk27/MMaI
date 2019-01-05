<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\Models\Event;
use App\SearchEngine\Events\QuerySearched;

final class QuerySearchedListener extends Listener {
    /**
     * @param QuerySearched $event
     * @return void
     */
    public function handle(QuerySearched $event): void {
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
