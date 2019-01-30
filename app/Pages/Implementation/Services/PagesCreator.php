<?php

namespace App\Pages\Implementation\Services;

use App\Attachments\AttachmentsFacade;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Routes\Models\Route;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Throwable;

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
     * @param array $pageData
     * @return Page
     * @throws Throwable
     */
    public function create(array $pageData): Page {
        $page = new Page([
            'website_id' => array_get($pageData, 'website_id'),

            'title' => array_get($pageData, 'title'),
            'lead' => array_get($pageData, 'lead'),
            'content' => array_get($pageData, 'content'),
            'notes' => array_get($pageData, 'notes'),

            'type' => array_get($pageData, 'type'),
            'status' => array_get($pageData, 'status'),
        ]);

        if (strlen($pageData['url']) > 0) {
            $route = new Route([
                'subdomain' => $page->website->slug ?? '',
                'url' => $pageData['url'],
            ]);

            $page->setRelation('route', $route);
        }

        $page->saveOrFail();

        return $page;
        // @todo attachments & tags
    }

}
