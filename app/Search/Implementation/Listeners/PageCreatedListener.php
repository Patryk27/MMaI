<?php

namespace App\Search\Implementation\Listeners;

use App\Pages\Events\PageCreated;
use App\Search\Implementation\Services\PagesIndexer;
use Illuminate\Contracts\Queue\ShouldQueue;

final class PageCreatedListener implements ShouldQueue {

    /** @var PagesIndexer */
    private $pagesIndexer;

    public function __construct(PagesIndexer $pagesIndexer) {
        $this->pagesIndexer = $pagesIndexer;
    }

    /**
     * @param PageCreated $event
     * @return void
     */
    public function handle(PageCreated $event): void {
        $this->pagesIndexer->index(
            $event->getPage()
        );
    }

}
