<?php

namespace App\Analytics\Implementation\Listeners;

use App\Analytics\AnalyticsFacade;
use App\Analytics\Models\Event;
use App\SearchEngine\Events\QuerySearched;
use Illuminate\Contracts\Queue\ShouldQueue;

final class QuerySearchedListener implements ShouldQueue
{

    /**
     * @var AnalyticsFacade
     */
    private $analyticsFacade;

    /**
     * @param AnalyticsFacade $analyticsFacade
     */
    public function __construct(
        AnalyticsFacade $analyticsFacade
    ) {
        $this->analyticsFacade = $analyticsFacade;
    }

    /**
     * @param QuerySearched $event
     * @return void
     */
    public function handle(QuerySearched $event): void
    {
        $query = $event->getQuery();

        $this->analyticsFacade->create(Event::TYPE_QUERY_SEARCHED, [
            'query' => [
                'query' => $query->getQuery(),
                'languageId' => $query->getLanguage() ? $query->getLanguage()->id : null,
            ],

            'matchedIds' => $event->getMatchedIds(),
            'numberOfMatchedIds' => count($event->getMatchedIds()),
        ]);
    }

}
