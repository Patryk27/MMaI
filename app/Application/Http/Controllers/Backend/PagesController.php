<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Pages\CreatePageRequest;
use App\Application\Http\Requests\Backend\Pages\UpdatePageRequest;
use App\Core\DataTables\Handler as DataTablesHandler;
use App\Core\Table\Renderer as TableRenderer;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPages;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Queries\GetAllWebsitesQuery;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PagesController extends Controller
{
    /** @var TableRenderer */
    private $tableRenderer;

    /** @var DataTablesHandler */
    private $dataTablesHandler;

    /** @var WebsitesFacade */
    private $websitesFacade;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        TableRenderer $tableRenderer,
        DataTablesHandler $dataTablesHandler,
        WebsitesFacade $websitesFacade,
        PagesFacade $pagesFacade
    ) {
        $this->tableRenderer = $tableRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->websitesFacade = $websitesFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return View
     * @throws WebsiteException
     */
    public function index(): View
    {
        $websites = $this->websitesFacade->queryMany(
            new GetAllWebsitesQuery()
        );

        $websites = $websites
            ->sortBy('name')
            ->pluck('name', 'id');

        return view('backend.views.pages.index', [
            'types' => __('base/models/page.enums.type'),
            'statuses' => __('base/models/page.enums.status'),
            'websites' => $websites,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        $this->tableRenderer->addColumns([
            'id' => 'backend.components.table.id',
            'website' => 'backend.components.pages.table.website',
            'type' => 'backend.components.pages.table.type',
            'title' => 'backend.components.pages.table.title',
            'status' => 'backend.components.pages.table.status',
            'createdAt' => 'backend.components.table.created-at',
            'actions' => 'backend.components.pages.table.actions',
        ]);

        $this->dataTablesHandler->setRowsFetcher(function (array $query): Collection {
            return $this->tableRenderer->render(
                $this->pagesFacade->queryMany(
                    new SearchPages($query)
                )
            );
        });

        $this->dataTablesHandler->setRowsCounter(function (array $query): int {
            return $this->pagesFacade->queryCount(
                new SearchPages($query)
            );
        });

        return $this->dataTablesHandler->handle($request);
    }

    /**
     * @return View
     */
    public function createPage(): View
    {
        return view('backend.views.pages.create', [
            'type' => Page::TYPE_PAGE,
        ]);
    }

    /**
     * @return View
     */
    public function createPost(): View
    {
        return view('backend.views.pages.create', [
            'type' => Page::TYPE_POST,
        ]);
    }

    /**
     * @param Page $page
     * @return View
     */
    public function edit(Page $page): View
    {
        return view('backend.views.pages.edit', [
            'page' => $page,
        ]);
    }
}
