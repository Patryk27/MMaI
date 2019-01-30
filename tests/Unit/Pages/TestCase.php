<?php

namespace Tests\Unit\Pages;

use App\Attachments\AttachmentsFacade;
use App\Attachments\AttachmentsFactory;
use App\Attachments\Implementation\Repositories\InMemoryAttachmentsRepository;
use App\Core\Repositories\InMemoryRepository;
use App\Pages\Implementation\Repositories\InMemoryPagesRepository;
use App\Pages\Implementation\Services\Searcher\InMemoryPagesSearcher;
use App\Pages\PagesFacade;
use App\Pages\PagesFactory;
use App\Tags\Implementation\Repositories\InMemoryTagsRepository;
use App\Tags\Implementation\Services\Searcher\InMemoryTagsSearcher;
use App\Tags\Models\Tag;
use App\Tags\TagsFacade;
use App\Tags\TagsFactory;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Filesystem\FilesystemAdapter;
use Illuminate\Support\Testing\Fakes\EventFake;
use League\Flysystem\Filesystem;
use League\Flysystem\Memory\MemoryAdapter;
use Tests\Unit\TestCase as BaseTestCase;
use Tests\Unit\Traits\CreatesAttachments;

abstract class TestCase extends BaseTestCase {

    use CreatesAttachments;

    /** @var FilesystemAdapter */
    protected $attachmentsFs;

    /** @var InMemoryAttachmentsRepository */
    protected $attachmentsRepository;

    /** @var InMemoryPagesRepository */
    protected $pagesRepository;

    /** @var InMemoryTagsRepository */
    protected $tagsRepository;

    /** @var AttachmentsFacade */
    protected $attachmentsFacade;

    /** @var PagesFacade */
    protected $pagesFacade;

    /** @var TagsFacade */
    protected $tagsFacade;

    /**
     * @return void
     */
    public function setUp(): void {
        parent::setUp();

        $eventsDispatcher = new EventFake(
            $this->app->make(EventsDispatcher::class)
        );

        /**
         * Prepare filesystems
         * -------------------
         */

        $this->attachmentsFs = new FilesystemAdapter(
            new Filesystem(
                new MemoryAdapter()
            )
        );

        /**
         * Prepare repositories
         * --------------------
         */

        $this->attachmentsRepository = new InMemoryAttachmentsRepository(
            new InMemoryRepository()
        );

        $this->pagesRepository = new InMemoryPagesRepository(
            new InMemoryRepository()
        );

        $this->tagsRepository = new InMemoryTagsRepository(
            new InMemoryRepository([
                new Tag([
                    'language_id' => 100,
                    'name' => 'First tag',
                ]),

                new Tag([
                    'language_id' => 100,
                    'name' => 'Second tag',
                ]),

                new Tag([
                    'language_id' => 200,
                    'name' => 'Third tag',
                ]),
            ])
        );

        /**
         * Prepare miscellaneous services
         * ------------------------------
         */

        $tagsSearcher = new InMemoryTagsSearcher();
        $pagesSearcher = new InMemoryPagesSearcher();

        /**
         * Instantiate facades
         * -------------------
         */

        $this->attachmentsFacade = AttachmentsFactory::build(
            $this->attachmentsFs,
            $this->attachmentsRepository
        );

        $this->tagsFacade = TagsFactory::build(
            $eventsDispatcher,
            $this->tagsRepository,
            $tagsSearcher
        );

        $this->pagesFacade = PagesFactory::build(
            $eventsDispatcher,
            $this->pagesRepository,
            $pagesSearcher,
            $this->attachmentsFacade,
            $this->tagsFacade
        );
    }

}
