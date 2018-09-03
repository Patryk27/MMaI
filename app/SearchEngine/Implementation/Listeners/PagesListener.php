<?php

namespace App\SearchEngine\Implementation\Listeners;

use App\Pages\Events\PageCreated;
use App\Pages\Events\PageUpdated;
use App\SearchEngine\Implementation\Services\PagesIndexer;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

final class PagesListener
{

    /**
     * @var EventsDispatcherContract
     */
    private $eventsDispatcher;

    /**
     * @var PagesIndexer
     */
    private $pagesIndexer;

    /**
     * @param EventsDispatcherContract $eventsDispatcher
     * @param PagesIndexer $pagesIndexer
     */
    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        PagesIndexer $pagesIndexer
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->pagesIndexer = $pagesIndexer;
    }

    /**
     * @return void
     */
    public function initialize(): void
    {
        $this->eventsDispatcher->listen(PageCreated::class, [$this, 'handlePageCreated']);
        $this->eventsDispatcher->listen(PageUpdated::class, [$this, 'handlePageUpdated']);
    }

    /**
     * @param PageCreated $event
     * @return void
     */
    public function handlePageCreated(PageCreated $event): void
    {
        $this->pagesIndexer->index(
            $event->getPage()
        );
    }

    /**
     * @param PageUpdated $event
     * @return void
     */
    public function handlePageUpdated(PageUpdated $event): void
    {
        $this->pagesIndexer->index(
            $event->getPage()
        );
    }

}