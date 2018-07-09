<?php

namespace App\Http\Controllers\Backend;

use App\CommandBus\Commands\Pages\CreateCommand as CreatePageCommand;
use App\CommandBus\Commands\Pages\UpdateCommand as UpdatePageCommand;
use App\Exceptions\Exception as AppException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Pages\UpsertRequest as PageUpsertRequest;
use App\Models\Page;
use App\Services\Core\Collection\Renderer as CollectionRenderer;
use App\Services\Core\DataTable\Handler as DataTableHandler;
use App\Services\PageVariants\Searcher as PageVariantsSearcher;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;
use Joselfonseca\LaravelTactician\CommandBusInterface;

class PagesController extends Controller
{

    /**
     * @var CommandBusInterface
     */
    private $commandBus;

    /**
     * @var PageVariantsSearcher
     */
    private $pageVariantsSearcher;

    /**
     * @var CollectionRenderer
     */
    private $collectionRenderer;

    /**
     * @var DataTableHandler
     */
    private $dataTableHandler;

    /**
     * @param CommandBusInterface $commandBus
     * @param PageVariantsSearcher $pageVariantsSearcher
     * @param CollectionRenderer $collectionRenderer
     * @param DataTableHandler $dataTableHandler
     */
    public function __construct(
        CommandBusInterface $commandBus,
        PageVariantsSearcher $pageVariantsSearcher,
        CollectionRenderer $collectionRenderer,
        DataTableHandler $dataTableHandler
    ) {
        $this->commandBus = $commandBus;
        $this->pageVariantsSearcher = $pageVariantsSearcher;
        $this->collectionRenderer = $collectionRenderer;
        $this->dataTableHandler = $dataTableHandler;
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
     *
     * @throws AppException
     */
    public function search(Request $request): array
    {
        $this->pageVariantsSearcher->filter([
            PageVariantsSearcher::FIELD_PAGE_TYPE => Page::TYPE_CMS,
        ]);

        $this->collectionRenderer->addColumns([
            'id' => 'backend.pages.pages.search.columns.id',
            'language' => 'backend.pages.pages.search.columns.language',
            'route' => 'backend.pages.pages.search.columns.route',
            'title' => 'backend.pages.pages.search.columns.title',
            'status' => 'backend.pages.pages.search.columns.status',
            'actions' => 'backend.pages.pages.search.columns.actions',
        ]);

        return $this->dataTableHandler->handle(
            $this->pageVariantsSearcher,
            $this->collectionRenderer,
            $request
        );
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
     */
    public function store(PageUpsertRequest $request): array
    {
        /**
         * @var Page $page
         */
        $page = $this->commandBus->dispatch(
            new CreatePageCommand($request->all())
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
     */
    public function update(Page $page, PageUpsertRequest $request): array
    {
        $this->commandBus->dispatch(
            new UpdatePageCommand($page, $request->all())
        );

        return [
            'redirectTo' => $page->getBackendEditUrl(),
        ];
    }

}