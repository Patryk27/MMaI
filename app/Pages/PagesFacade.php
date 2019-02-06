<?php

namespace App\Pages;

use App\Pages\Exceptions\PageException;
use App\Pages\Exceptions\PageNotFoundException;
use App\Pages\Implementation\Services\PagesCreator;
use App\Pages\Implementation\Services\PagesQuerier;
use App\Pages\Implementation\Services\PagesRenderer;
use App\Pages\Implementation\Services\PagesUpdater;
use App\Pages\Models\Page;
use App\Pages\Policies\PagePolicy;
use App\Pages\Queries\PagesQuery;
use App\Pages\Requests\CreatePage;
use App\Pages\Requests\UpdatePage;
use App\Pages\ValueObjects\RenderedPage;
use Exception;
use Gate;
use Illuminate\Support\Collection;

final class PagesFacade {

    /** @var PagesCreator */
    private $pagesCreator;

    /** @var PagesUpdater */
    private $pagesUpdater;

    /** @var PagesRenderer */
    private $pagesRenderer;

    /** @var PagesQuerier */
    private $pagesQuerier;

    public function __construct(
        PagesCreator $pagesCreator,
        PagesUpdater $pagesUpdater,
        PagesRenderer $pagesRenderer,
        PagesQuerier $pagesQuerier
    ) {
        $this->pagesCreator = $pagesCreator;
        $this->pagesUpdater = $pagesUpdater;
        $this->pagesRenderer = $pagesRenderer;
        $this->pagesQuerier = $pagesQuerier;
    }

    /**
     * @return void
     */
    public function boot(): void {
        Gate::policy(Page::class, PagePolicy::class);
    }

    /**
     * Creates a brand-new page from given data.
     *
     * @param CreatePage $request
     * @return Page
     */
    public function create(CreatePage $request): Page {
        return $this->pagesCreator->create($request);
    }

    /**
     * Updates an already existing page.
     *
     * @param Page $page
     * @param UpdatePage $request
     * @return void
     */
    public function update(Page $page, UpdatePage $request): void {
        $this->pagesUpdater->update($page, $request);
    }

    /**
     * Renders given page and returns the rendered VO.
     *
     * @param Page $page
     * @return RenderedPage
     * @throws Exception
     */
    public function render(Page $page): RenderedPage {
        return $this->pagesRenderer->render($page);
    }

    /**
     * Returns the first page matching given query.
     * Throws an exception if no such page exists.
     *
     * @param PagesQuery $query
     * @return Page
     * @throws PageException
     * @throws PageNotFoundException
     */
    public function queryOne(PagesQuery $query): Page {
        $pages = $this->queryMany($query);

        if ($pages->isEmpty()) {
            throw new PageNotFoundException();
        }

        return $pages->first();
    }

    /**
     * Returns all pages matching given query.
     *
     * @param PagesQuery $query
     * @return Collection|Page[]
     * @throws PageException
     */
    public function queryMany(PagesQuery $query): Collection {
        return $this->pagesQuerier->query($query);
    }

    /**
     * Returns number of pages matching given query.
     *
     * @param PagesQuery $query
     * @return int
     * @throws PageException
     */
    public function queryCount(PagesQuery $query): int {
        return $this->pagesQuerier->count($query);
    }

}
