<?php

namespace App\Pages\Implementation\Services\Pages;

use App\Attachments\AttachmentsFacade;
use App\Attachments\Exceptions\AttachmentException;
use App\Attachments\Queries\GetAttachmentByIdQuery;
use App\Pages\Events\PageCreated;
use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Implementation\Services\PageVariants\PageVariantsCreator;
use App\Pages\Models\Page;
use App\Tags\Exceptions\TagException;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;

/**
 * @see \Tests\Unit\Pages\CreateTest
 */
class PagesCreator
{

    /**
     * @var EventsDispatcherContract
     */
    private $eventsDispatcher;

    /**
     * @var PagesRepository
     */
    private $pagesRepository;

    /**
     * @var PageVariantsCreator
     */
    private $pageVariantsCreator;

    /**
     * @var PagesValidator
     */
    private $pagesValidator;

    /**
     * @var AttachmentsFacade
     */
    private $attachmentsFacade;

    /**
     * @param EventsDispatcherContract $eventsDispatcher
     * @param PagesRepository $pagesRepository
     * @param PageVariantsCreator $pageVariantsCreator
     * @param PagesValidator $pagesValidator
     * @param AttachmentsFacade $attachmentsFacade
     */
    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        PagesRepository $pagesRepository,
        PageVariantsCreator $pageVariantsCreator,
        PagesValidator $pagesValidator,
        AttachmentsFacade $attachmentsFacade
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->pagesRepository = $pagesRepository;
        $this->pageVariantsCreator = $pageVariantsCreator;
        $this->pagesValidator = $pagesValidator;
        $this->attachmentsFacade = $attachmentsFacade;
    }

    /**
     * @param array $pageData
     * @return Page
     *
     * @throws AttachmentException
     * @throws PageException
     * @throws TagException
     */
    public function create(array $pageData): Page
    {
        $page = new Page([
            'type' => array_get($pageData, 'page.type'),
            'notes' => array_get($pageData, 'page.notes'),
        ]);

        $this->savePageVariants($page, array_get($pageData, 'pageVariants') ?? []);
        $this->saveAttachments($page, array_get($pageData, 'attachment_ids') ?? []);
        $this->save($page);

        return $page;
    }

    /**
     * @param Page $page
     * @param array $pageVariantsData
     * @return void
     *
     * @throws PageException
     * @throws TagException
     */
    private function savePageVariants(Page $page, array $pageVariantsData): void
    {
        foreach ($pageVariantsData as $pageVariantData) {
            $page->pageVariants->push(
                $this->pageVariantsCreator->create($page, $pageVariantData)
            );
        }
    }

    /**
     * @param Page $page
     * @param int[] $attachmentIds
     * @return void
     *
     * @throws AttachmentException
     */
    private function saveAttachments(Page $page, array $attachmentIds): void
    {
        foreach ($attachmentIds as $attachmentId) {
            $attachment = $this->attachmentsFacade->queryOne(
                new GetAttachmentByIdQuery($attachmentId)
            );

            $page->attachments->push($attachment);
        }
    }

    /**
     * @param Page $page
     * @return void
     *
     * @throws PageException
     */
    private function save(Page $page): void
    {
        $this->pagesValidator->validate($page);

        $this->pagesRepository->persist($page);

        $this->eventsDispatcher->dispatch(
            new PageCreated($page)
        );
    }

}
