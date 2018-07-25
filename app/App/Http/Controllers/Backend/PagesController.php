<?php

namespace App\App\Http\Controllers\Backend;

use App\App\Http\Controllers\Controller;
use App\App\Http\Requests\Backend\Pages\UpsertRequest as PageUpsertRequest;
use App\Core\Exceptions\Exception as AppException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;

class PagesController extends Controller
{

    /**
     * @var PagesFacade
     */
    private $pagesFacade;

    /**
     * @param PagesFacade $pagesFacade
     */
    public function __construct(
        PagesFacade $pagesFacade
    ) {
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
     *
     * @throws AppException
     */
    public function search(Request $request): array
    {
        throw new AppException('Not implemented.'); // @todo

//        $this->pageVariantsSearcher->filter([
//            PageVariantsSearcher::FIELD_PAGE_TYPE => Page::TYPE_CMS,
//        ]);
//
//        $this->collectionRenderer->addColumns([
//            'id' => 'backend.pages.pages.search.columns.id',
//            'language' => 'backend.pages.pages.search.columns.language',
//            'route' => 'backend.pages.pages.search.columns.route',
//            'title' => 'backend.pages.pages.search.columns.title',
//            'status' => 'backend.pages.pages.search.columns.status',
//            'actions' => 'backend.pages.pages.search.columns.actions',
//        ]);
//
//        return $this->dataTableHandler->handle(
//            $this->pageVariantsSearcher,
//            $this->collectionRenderer,
//            $request
//        );
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
     */
    public function update(Page $page, PageUpsertRequest $request): array
    {
        $this->pagesFacade->update($page, $request->all());

        return [
            'redirectTo' => $page->getBackendEditUrl(),
        ];
    }

}