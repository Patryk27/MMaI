<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Pages\CreatePageRequest;
use App\Application\Http\Requests\Backend\Pages\UpdatePageRequest;
use App\Core\Exceptions\Exception as AppException;
use App\Core\Services\Collection\Renderer as CollectionRenderer;
use App\Core\Services\DataTables\Handler as DataTablesHandler;
use App\Languages\Exceptions\LanguageException;
use App\Languages\LanguagesFacade;
use App\Languages\Queries\GetAllLanguagesQuery;
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
     * @var LanguagesFacade
     */
    private $languagesFacade;

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param CollectionRenderer $collectionRenderer
     * @param DataTablesHandler $dataTablesHandler
     * @param LanguagesFacade $languagesFacade
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        CollectionRenderer $collectionRenderer,
        DataTablesHandler $dataTablesHandler,
        LanguagesFacade $languagesFacade,
        PagesFacade $pagesFacade
    ) {
        $this->collectionRenderer = $collectionRenderer;
        $this->dataTablesHandler = $dataTablesHandler;
        $this->languagesFacade = $languagesFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return ViewContract
     *
     * @throws LanguageException
     */
    public function index(): ViewContract
    {
        $languages = $this->languagesFacade->queryMany(
            new GetAllLanguagesQuery()
        );

        $languages = $languages->sortBy('english_name');
        $languages = $languages->pluck('english_name', 'id');

        return view('backend.pages.pages.index', [
            'types' => __('base/models/page.enums.type'),
            'languages' => $languages,
            'statuses' => __('base/models/page-variant.enums.status'),
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        $this->collectionRenderer->addColumns([
            'id' => 'backend.pages.pages.search.columns.id',
            'type' => 'backend.pages.pages.search.columns.type',
            'language' => 'backend.pages.pages.search.columns.language',
            'title' => 'backend.pages.pages.search.columns.title',
            'status' => 'backend.pages.pages.search.columns.status',
            'created_at' => 'backend.pages.pages.search.columns.created-at', // @todo shouldn't it say "created-at", just like the tags do?
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
     * @param CreatePageRequest $request
     * @return array
     *
     * @throws AppException
     */
    public function store(CreatePageRequest $request): array
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
     * @param UpdatePageRequest $request
     * @param Page $page
     * @return array
     *
     * @throws AppException
     */
    public function update(UpdatePageRequest $request, Page $page): array
    {
        $this->pagesFacade->update(
            $page,
            $request->all()
        );

        return [
            'redirectTo' => $page->getBackendEditUrl(),
        ];
    }

}
