<?php

namespace App\Pages;

use App\Core\Exceptions\Exception as AppException;
use App\Pages\Exceptions\PageVariantNotFoundException;
use App\Pages\Implementation\Services\Pages\PagesCreator;
use App\Pages\Implementation\Services\Pages\PagesUpdater;
use App\Pages\Implementation\Services\PageVariants\PageVariantsQuerier;
use App\Pages\Implementation\Services\PageVariants\PageVariantsRenderer;
use App\Pages\Models\Page;
use App\Pages\Models\PageVariant;
use App\Pages\Queries\PageVariantsQueryInterface;
use App\Pages\ValueObjects\RenderedPage;
use Exception;
use Illuminate\Support\Collection;

final class PagesFacade
{

    /**
     * @var PagesCreator
     */
    private $pagesCreator;

    /**
     * @var PagesUpdater
     */
    private $pagesUpdater;

    /**
     * @var PageVariantsQuerier
     */
    private $pageVariantsQuerier;

    /**
     * @var PageVariantsRenderer
     */
    private $pageVariantsRenderer;

    /**
     * @param PagesCreator $pagesCreator
     * @param PagesUpdater $pagesUpdater
     * @param PageVariantsQuerier $pageVariantsQuerier
     * @param PageVariantsRenderer $pageVariantsRenderer
     */
    public function __construct(
        PagesCreator $pagesCreator,
        PagesUpdater $pagesUpdater,
        PageVariantsQuerier $pageVariantsQuerier,
        PageVariantsRenderer $pageVariantsRenderer
    ) {
        $this->pagesCreator = $pagesCreator;
        $this->pagesUpdater = $pagesUpdater;
        $this->pageVariantsQuerier = $pageVariantsQuerier;
        $this->pageVariantsRenderer = $pageVariantsRenderer;
    }

    /**
     * Creates a brand-new page from given data.
     *
     * @see \App\App\Http\Requests\Backend\Pages\UpsertRequest
     *
     * @param array $pageData
     * @return Page
     *
     * @throws AppException
     */
    public function create(array $pageData): Page
    {
        return $this->pagesCreator->create($pageData);
    }

    /**
     * Updates an already existing page.
     *
     * @see \App\App\Http\Requests\Backend\Pages\UpsertRequest
     *
     * @param Page $page
     * @param array $pageData
     * @return void
     *
     * @throws AppException
     */
    public function update(Page $page, array $pageData): void
    {
        $this->pagesUpdater->update($page, $pageData);
    }

    /**
     * Renders given page variant and returns the rendered VO.
     *
     * @param PageVariant $pageVariant
     * @return RenderedPage
     *
     * @throws Exception
     */
    public function render(PageVariant $pageVariant): RenderedPage
    {
        return $this->pageVariantsRenderer->render($pageVariant);
    }

    /**
     * Returns first page variant matching given query.
     *
     * @param PageVariantsQueryInterface $query
     * @return PageVariant
     *
     * @throws PageVariantNotFoundException
     */
    public function queryOne(PageVariantsQueryInterface $query): PageVariant
    {
        $pageVariants = $this->queryMany($query);

        if ($pageVariants->isEmpty()) {
            throw new PageVariantNotFoundException();
        }

        return $pageVariants->first();
    }

    /**
     * Returns all page variants matching given query.
     *
     * @param PageVariantsQueryInterface $query
     * @return Collection|PageVariant[]
     */
    public function queryMany(PageVariantsQueryInterface $query): Collection
    {
        return $this->pageVariantsQuerier->query($query);
    }

    /**
     * Returns number of page variants matching given query.
     *
     * @param PageVariantsQueryInterface $query
     * @return int
     */
    public function queryCount(PageVariantsQueryInterface $query): int
    {
        return $this->pageVariantsQuerier->queryCount($query);
    }

}