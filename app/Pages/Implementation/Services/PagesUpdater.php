<?php

namespace App\Pages\Implementation\Services;

use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Pages\Requests\UpdatePage;
use App\Routes\Models\Route;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

class PagesUpdater {

    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var PagesRepository */
    private $pagesRepository;

    /** @var PagesValidator */
    private $pagesValidator;

    /**
     * @param EventsDispatcher $eventsDispatcher
     * @param PagesRepository $pagesRepository
     * @param PagesValidator $pagesValidator
     */
    public function __construct(
        EventsDispatcher $eventsDispatcher,
        PagesRepository $pagesRepository,
        PagesValidator $pagesValidator
    ) {
        $this->pagesRepository = $pagesRepository;
        $this->pagesValidator = $pagesValidator;
        $this->eventsDispatcher = $eventsDispatcher;
    }

    /**
     * @param Page $page
     * @param UpdatePage $request
     * @return void
     */
    public function update(Page $page, UpdatePage $request): void {
        // @todo attachments & tags
        // @todo dispatch appropriate event

        $page->fill([
            'title' => $request->get('title'),
            'lead' => $request->get('lead'),
            'content' => $request->get('content'),
            'notes' => $request->get('notes'),

            'type' => $request->get('type'),
            'status' => $request->get('status'),
        ]);

        if ($request->hasUrl()) {
            $route = new Route([
                'subdomain' => $page->website->slug ?? '',
                'url' => $request->get('url'),
            ]);

            $page->setRelation('route', $route);
        } else {
            $page->setRelation('route', null);
        }

        $this->pagesRepository->persist($page);
    }

}
