<?php

namespace App\Pages\Implementation\Services;

use App\Attachments\AttachmentsFacade;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Pages\Requests\CreatePage;
use App\Routes\Models\Route;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

class PagesCreator {

    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var PagesRepository */
    private $pagesRepository;

    /** @var PagesValidator */
    private $pagesValidator;

    /** @var AttachmentsFacade */
    private $attachmentsFacade;

    public function __construct(
        EventsDispatcher $eventsDispatcher,
        PagesRepository $pagesRepository,
        PagesValidator $pagesValidator,
        AttachmentsFacade $attachmentsFacade
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->pagesRepository = $pagesRepository;
        $this->pagesValidator = $pagesValidator;
        $this->attachmentsFacade = $attachmentsFacade;
    }

    /**
     * @param CreatePage $request
     * @return Page
     */
    public function create(CreatePage $request): Page {
        // @todo attachments & tags
        // @todo dispatch appropriate event

        $page = new Page([
            'website_id' => $request->get('websiteId'),

            'title' => $request->get('title'),
            'lead' => $request->get('lead'),
            'content' => $request->get('content'),
            'notes' => $request->get('notes'),

            'type' => $request->get('type'),
            'status' => $request->get('status'),
        ]);

        if (strlen($request->get('url')) > 0) {
            $route = new Route([
                'subdomain' => $page->website->slug ?? '',
                'url' => $request->get('url'),
            ]);

            $page->setRelation('route', $route);
        }

        $this->pagesRepository->persist($page);

        return $page;
    }

}
