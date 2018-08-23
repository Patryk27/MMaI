<?php

namespace App\Pages\Implementation\Services\Pages;

use App\Pages\Events\PageCreated;
use App\Pages\Exceptions\PageException;
use App\Pages\Implementation\Repositories\PagesRepositoryInterface;
use App\Pages\Implementation\Services\PageVariants\PageVariantsCreator;
use App\Pages\Models\Page;
use App\Tags\Exceptions\TagException;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

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
     * @var PagesRepositoryInterface
     */
    private $pagesRepository;

    /**
     * @var PageVariantsCreator
     */
    private $pageVariantsCreator;

    /**
     * @param EventsDispatcherContract $eventsDispatcher
     * @param PagesRepositoryInterface $pagesRepository
     * @param PageVariantsCreator $pageVariantsCreator
     */
    public function __construct(
        EventsDispatcherContract $eventsDispatcher,
        PagesRepositoryInterface $pagesRepository,
        PageVariantsCreator $pageVariantsCreator
    ) {
        $this->eventsDispatcher = $eventsDispatcher;
        $this->pagesRepository = $pagesRepository;
        $this->pageVariantsCreator = $pageVariantsCreator;
    }

    /**
     * @param array $pageData
     * @return Page
     *
     * @throws PageException
     * @throws TagException
     */
    public function create(array $pageData): Page
    {
        // Create a new page
        $page = new Page([
            'type' => array_get($pageData, 'page.type'),
        ]);

        // We are manually setting the "page variants" relationship, so that
        // Eloquent does not try to interfere and fetch it (which would fail
        // during unit testing).
        $page->setRelation('pageVariants', new EloquentCollection());

        foreach (array_get($pageData, 'pageVariants', []) as $pageVariantData) {
            $page->pageVariants->push(
                $this->pageVariantsCreator->create($page, $pageVariantData)
            );
        }

        $this->pagesRepository->persist($page);

        $this->eventsDispatcher->dispatch(
            new PageCreated($page)
        );

        return $page;
    }

}