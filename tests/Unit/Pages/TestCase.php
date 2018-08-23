<?php

namespace Tests\Unit\Pages;

use App\Core\Repositories\InMemoryRepository;
use App\Pages\Implementation\Repositories\InMemoryPagesRepository;
use App\Pages\Implementation\Services\PageVariants\Searcher\InMemoryPageVariantsSearcher;
use App\Pages\PagesFacade;
use App\Pages\PagesFactory;
use App\Tags\Implementation\Repositories\InMemoryTagsRepository;
use App\Tags\Implementation\Services\Searcher\InMemoryTagsSearcher;
use App\Tags\Models\Tag;
use App\Tags\TagsFacade;
use App\Tags\TagsFactory;
use Illuminate\Events\Dispatcher as EventsDispatcher;
use Illuminate\Support\Testing\Fakes\EventFake;
use Tests\Unit\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{

    /**
     * @var InMemoryTagsRepository
     */
    protected $tagsRepository;

    /**
     * @var InMemoryPagesRepository
     */
    protected $pagesRepository;

    /**
     * @var TagsFacade
     */
    protected $tagsFacade;

    /**
     * @var PagesFacade
     */
    protected $pagesFacade;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $eventsDispatcher = new EventFake(
            $this->app->make(EventsDispatcher::class)
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

        $this->pagesRepository = new InMemoryPagesRepository(
            new InMemoryRepository()
        );

        $tagsSearcher = new InMemoryTagsSearcher();
        $pageVariantsSearcher = new InMemoryPageVariantsSearcher();

        $this->tagsFacade = TagsFactory::build(
            $this->tagsRepository,
            $tagsSearcher
        );

        $this->pagesFacade = PagesFactory::build(
            $eventsDispatcher,
            $this->pagesRepository,
            $pageVariantsSearcher,
            $this->tagsFacade
        );
    }

}