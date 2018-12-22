<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Pages\CreatePageRequest;
use App\Application\Http\Requests\Backend\Pages\UpdatePageRequest;
use App\Core\DataTables\Handler as DataTablesHandler;
use App\Core\Table\Renderer as TableRenderer;
use App\Languages\Exceptions\LanguageException;
use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPages;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PagesController extends Controller
{
    /** @var TableRenderer */
    private $tableRenderer;

    /** @var DataTablesHandler */
    private $dataTablesHandler;

    /** @var LanguagesFacade */
    private $languagesFacade;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        TableRenderer $tableRenderer,
        DataTablesHandler $dataTablesHandler,
        LanguagesFacade $languagesFacade,
        PagesFacade $pagesFacade
    ) {
        $this->tableRenderer = $tableRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->languagesFacade = $languagesFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return ViewContract
     * @throws LanguageException
     */
    public function index(): ViewContract
    {
        $languages = $this->languagesFacade->queryMany(
            new GetAllLanguagesQuery()
        );

        $languages = $languages->sortBy('english_name');
        $languages = $languages->pluck('english_name', 'id');

        return view('backend.views.pages.index', [
            'types' => __('base/models/page.enums.type'),
            'languages' => $languages,
            'statuses' => __('base/models/page.enums.status'),
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
            'type' => 'backend.components.pages.table.type',
            'language' => 'backend.components.pages.table.language',
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
     * @return ViewContract
     */
    public function createPage(): ViewContract
    {
        return view('backend.views.pages.create', [
            'type' => Page::TYPE_PAGE,
        ]);
    }

    /**
     * @return ViewContract
     */
    public function createPost(): ViewContract
    {
        return view('backend.views.pages.create', [
            'type' => Page::TYPE_POST,
        ]);
    }

    /**
     * @param CreatePageRequest $request
     * @return array
     */
    public function store(CreatePageRequest $request): array
    {
        $page = $this->pagesFacade->create(
            $request->all()
        );

        return [
            'redirectTo' => $page->getEditUrl(),
        ];
    }

    /**
     * @param Page $page
     * @return ViewContract
     */
    public function edit(Page $page): ViewContract
    {
        return view('backend.views.pages.edit', [
            'page' => $page,
        ]);
    }

    /**
     * @param UpdatePageRequest $request
     * @param Page $page
     * @return array
     */
    public function update(UpdatePageRequest $request, Page $page): array
    {
        $this->pagesFacade->update(
            $page,
            $request->all()
        );

        return [
            'redirectTo' => $page->getEditUrl(),
        ];
    }
}
