<?php

namespace App\Pages\Implementation\Services;

use App\Attachments\AttachmentsFacade;
use App\Attachments\Models\Attachment;
use App\Attachments\Queries\GetAttachmentById;
use App\Pages\Events\PageCreated;
use App\Pages\Events\PageUpdated;
use App\Pages\Implementation\Repositories\PagesRepository;
use App\Pages\Models\Page;
use App\Pages\Requests\CreatePage;
use App\Pages\Requests\UpdatePage;
use App\Routes\Models\Route;
use App\Tags\Models\Tag;
use App\Tags\Queries\GetTagById;
use App\Tags\TagsFacade;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Foundation\Http\FormRequest;

class PagesModifier {

    /** @var EventsDispatcher */
    private $eventsDispatcher;

    /** @var PagesRepository */
    private $pagesRepository;

    /** @var PagesValidator */
    private $pagesValidator;

    /** @var AttachmentsFacade */
    private $attachmentsFacade;

    /** @var TagsFacade */
    private $tagsFacade;

    public function __construct(
        EventsDispatcher $eventsDispatcher,
        PagesRepository $pagesRepository,
        PagesValidator $pagesValidator,
        AttachmentsFacade $attachmentsFacade,
        TagsFacade $tagsFacade
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->pagesRepository = $pagesRepository;
        $this->pagesValidator = $pagesValidator;
        $this->attachmentsFacade = $attachmentsFacade;
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @param CreatePage $request
     * @return Page
     */
    public function create(CreatePage $request): Page {
        $page = new Page();

        $this->processPage($page, $request);
        $this->processRoute($page, $request->get('url') ?? '');
        $this->processAttachments($page, $request->get('attachment_ids'));
        $this->processTags($page, $request->get('tag_ids'));

        $this->pagesRepository->persist($page);
        $this->eventsDispatcher->dispatch(new PageCreated($page));

        return $page;
    }

    /**
     * @param Page $page
     * @param UpdatePage $request
     * @return void
     */
    public function update(Page $page, UpdatePage $request): void {
        $this->processPage($page, $request);
        $this->processRoute($page, $request->get('url') ?? '');
        $this->processAttachments($page, $request->get('attachment_ids'));
        $this->processTags($page, $request->get('tag_ids'));

        $this->pagesRepository->persist($page);
        $this->eventsDispatcher->dispatch(new PageUpdated($page));
    }

    /**
     * @param Page $page
     * @param FormRequest $request
     * @return void
     */
    private function processPage(Page $page, FormRequest $request): void {
        $page->fill($request->only([
            'website_id',
            'title',
            'lead',
            'type',
            'status',
        ]));

        $page->content = $request->get('content') ?? '';
        $page->notes = $request->get('notes') ?? '';
    }

    /**
     * @param Page $page
     * @param string $url
     * @return void
     */
    private function processRoute(Page $page, string $url): void {
        if (strlen($url) > 0) {
            $route = new Route([
                'subdomain' => $page->website->slug,
                'url' => $url,
            ]);
        } else {
            $route = null;
        }

        $page->setRelation('route', $route);
    }

    /**
     * @param Page $page
     * @param int[] $attachmentIds
     * @return void
     */
    private function processAttachments(Page $page, array $attachmentIds): void {
        $attachments = array_map(function (int $attachmentId): Attachment {
            return $this->attachmentsFacade->queryOne(
                new GetAttachmentById($attachmentId)
            );
        }, $attachmentIds);

        $page->setRelation('attachments', new EloquentCollection($attachments));
    }

    /**
     * @param Page $page
     * @param array $tagIds
     * @return void
     */
    private function processTags(Page $page, array $tagIds): void {
        $tags = array_map(function (int $tagId): Tag {
            return $this->tagsFacade->queryOne(
                new GetTagById($tagId)
            );
        }, $tagIds);

        $page->setRelation('tags', new EloquentCollection($tags));
    }

}
