<?php

namespace App\Pages\Implementation\Services;

use App\Attachments\AttachmentsFacade;
use App\Attachments\Exceptions\AttachmentException;
use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Tags\Exceptions\TagException;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;

class PagesUpdater {
    /**
     * @var EventsDispatcher
     */
    private $eventsDispatcher;

    /**
     * @var PagesRepository
     */
    private $pagesRepository;

    /**
     * @var PagesValidator
     */
    private $pagesValidator;

    /**
     * @var AttachmentsFacade
     */
    private $attachmentsFacade;

    /**
     * @param Page $page
     * @param array $pageData
     * @return void
     *
     * @throws AttachmentException
     * @throws PageException
     * @throws TagException
     */
    public function update(Page $page, array $pageData): void {
    }
}
