<?php

namespace App\App\Http\Controllers\Backend;

use App\App\Http\Controllers\Controller;
use App\App\Http\Requests\Backend\Pages\UpsertRequest as PageUpsertRequest;
use App\Core\Exceptions\Exception as AppException;
use App\Core\Services\Collection\Renderer as CollectionRenderer;
use App\Core\Services\DataTables\Handler as DataTablesHandler;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPageVariantsQuery;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PagesController extends Controller
{

    /**
     * @var CollectionRenderer
     */
    private $collectionRenderer;

    /**
     * @var DataTablesHandler
     */
    private $dataTablesHandler;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param CollectionRenderer $collectionRenderer
     * @param DataTablesHandler $dataTablesHandler
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        CollectionRenderer $collectionRenderer,
        DataTablesHandler $dataTablesHandler,
        PagesFacade $pagesFacade
    ) {
        $this->collectionRenderer = $collectionRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return ViewContract
     */
    public function index(): ViewContract
    {
        return view('backend.pages.pages.index');
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        $this->collectionRenderer->addColumns([
            'id' => 'backend.pages.pages.search.columns.id',
            'language' => 'backend.pages.pages.search.columns.language',
            'route' => 'backend.pages.pages.search.columns.route',
            'title' => 'backend.pages.pages.search.columns.title',
            'status' => 'backend.pages.pages.search.columns.status',
            'created_at' => 'backend.pages.pages.search.columns.created-at',
            'actions' => 'backend.pages.pages.search.columns.actions',
        ]);

        $this->dataTablesHandler->setQueryHandler(function (array $query): Collection {
            return $this->collectionRenderer->render(
                $this->pagesFacade->queryMany(
                    new SearchPageVariantsQuery($query)
                )
            );
        });

        $this->dataTablesHandler->setQueryCountHandler(function (array $query): int {
            return $this->pagesFacade->queryCount(
                new SearchPageVariantsQuery($query)
            );
        });

        return $this->dataTablesHandler->handle($request);
    }

    /**
     * @return ViewContract
     */
    public function create(): ViewContract
    {
        return view('backend.pages.pages.create');
    }

    /**
     * @param PageUpsertRequest $request
     * @return array
     *
     * @throws AppException
     */
    public function store(PageUpsertRequest $request): array
    {
        $page = $this->pagesFacade->create(
            $request->all()
        );

        return [
            'redirectTo' => $page->getBackendEditUrl(),
        ];
    }

    /**
     * @param Page $page
     * @return ViewContract
     */
    public function edit(Page $page): ViewContract
    {
        return view('backend.pages.pages.edit', [
            'page' => $page,
        ]);
    }

    /**
     * @param Page $page
     * @param PageUpsertRequest $request
     * @return array
     *
     * @throws AppException
     */
    public function update(Page $page, PageUpsertRequest $request): array
    {
        $this->pagesFacade->update($page, $request->all());

        return [
            'redirectTo' => $page->getBackendEditUrl(),
        ];
    }

}