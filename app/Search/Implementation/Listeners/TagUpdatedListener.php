<?php

namespace App\Search\Implementation\Listeners;

use App\Pages\Exceptions\PageException;
use App\Pages\PagesFacade;
use App\Pages\Queries\GetPagesByTagId;
use App\Search\SearchFacade;
use App\Tags\Events\TagUpdated;
use Illuminate\Contracts\Queue\ShouldQueue;

final class TagUpdatedListener implements ShouldQueue {

    /** @var PagesFacade */
    private $pagesFacade;

    /** @var SearchFacade */
    private $searchFacade;

    public function __construct(
        PagesFacade $pagesFacade,
        SearchFacade $searchFacade
    ) {
        $this->pagesFacade = $pagesFacade;
        $this->searchFacade = $searchFacade;
    }

    /**
     * @param TagUpdated $event
     * @return void
     * @throws PageException
     */
    public function handle(TagUpdated $event): void {
        $pages = $this->pagesFacade->queryMany(
            new GetPagesByTagId(
                $event->getTag()->id
            )
        );

        foreach ($pages as $page) {
            $this->searchFacade->index($page);
        }
    }

}
