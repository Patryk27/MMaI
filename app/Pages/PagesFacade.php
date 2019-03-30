<?php

namespace App\Pages;

use App\Grid\Query\GridQuery;
use App\Grid\Response\GridResponse;
use App\Grid\Schema\GridSchema;
use App\Pages\Exceptions\PageException;
use App\Pages\Exceptions\PageNotFoundException;
use App\Pages\Implementation\Services\Grid\PagesGridQueryExecutor;
use App\Pages\Implementation\Services\Grid\PagesGridSchemaProvider;
use App\Pages\Implementation\Services\PagesModifier;
use App\Pages\Implementation\Services\PagesQuerier;
use App\Pages\Implementation\Services\PagesRenderer;
use App\Pages\Models\Page;
use App\Pages\Policies\PagePolicy;
use App\Pages\Queries\PagesQuery;
use App\Pages\Requests\CreatePage;
use App\Pages\Requests\UpdatePage;
use App\Pages\ValueObjects\RenderedPage;
use App\Websites\Exceptions\WebsiteException;
use Exception;
use Gate;
use Illuminate\Support\Collection;

final class PagesFacade {

    /** @var PagesModifier */
    private $pagesModifier;

    /** @var PagesRenderer */
    private $pagesRenderer;

    /** @var PagesQuerier */
    private $pagesQuerier;

    /** @var PagesGridQueryExecutor */
    private $pagesGridQueryExecutor;

    /** @var PagesGridSchemaProvider */
    private $pagesGridSchemaProvider;

    public function __construct(
        PagesModifier $pagesModifier,
        PagesRenderer $pagesRenderer,
        PagesQuerier $pagesQuerier,
        PagesGridQueryExecutor $pagesGridQueryExecutor,
        PagesGridSchemaProvider $pagesGridSchemaProvider
    ) {
        $this->pagesModifier = $pagesModifier;
        $this->pagesRenderer = $pagesRenderer;
        $this->pagesQuerier = $pagesQuerier;
        $this->pagesGridQueryExecutor = $pagesGridQueryExecutor;
        $this->pagesGridSchemaProvider = $pagesGridSchemaProvider;
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
        return $this->pagesModifier->create($request);
    }

    /**
     * Updates an already existing page.
     *
     * @param Page $page
     * @param UpdatePage $request
     * @return void
     */
    public function update(Page $page, UpdatePage $request): void {
        $this->pagesModifier->update($page, $request);
    }

    /**
     * Renders given page and returns the rendered object.
     *
     * @param Page $page
     * @return RenderedPage
     * @throws Exception
     */
    public function render(Page $page): RenderedPage {
        return $this->pagesRenderer->render($page);
    }

    /**
     * Returns the first page matching given query; throws an exception if no
     * such page exists.
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
     * Executes specified grid query, returning all the matching pages in
     * response.
     *
     * @param GridQuery $query
     * @return GridResponse
     */
    public function executeGridQuery(GridQuery $query): GridResponse {
        return $this->pagesGridQueryExecutor->executeQuery($query);
    }

    /**
     * Returns schema for grid of pages.
     *
     * @return GridSchema
     * @throws WebsiteException
     */
    public function prepareGridSchema(): GridSchema {
        return $this->pagesGridSchemaProvider->prepareSchema();
    }

}
