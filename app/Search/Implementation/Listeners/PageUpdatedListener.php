<?php

namespace App\Search\Implementation\Listeners;

use App\Pages\Events\PageUpdated;
use App\Search\Implementation\Services\PagesIndexer;
use Illuminate\Contracts\Queue\ShouldQueue;

final class PageUpdatedListener implements ShouldQueue {

    /** @var PagesIndexer */
    private $pagesIndexer;

    public function __construct(PagesIndexer $pagesIndexer) {
        $this->pagesIndexer = $pagesIndexer;
    }

    /**
     * @param PageUpdated $event
     * @return void
     */
    public function handle(PageUpdated $event): void {
        $this->pagesIndexer->index(
            $event->getPage()
        );
    }

}
