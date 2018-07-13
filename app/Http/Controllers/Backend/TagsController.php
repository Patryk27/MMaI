<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\LanguagesRepository;
use App\Services\Core\Collection\Renderer as CollectionRenderer;
use App\Services\Core\DataTable\Handler as DataTableHandler;
use App\Services\Tags\Searcher as TagsSearcher;
use Illuminate\Contracts\View\View as ViewContract;
use Illuminate\Http\Request;

class TagsController extends Controller
{

    /**
     * @var LanguagesRepository
     */
    private $languagesRepository;

    /**
     * @var TagsSearcher
     */
    private $tagsSearcher;

    /**
     * @var CollectionRenderer
     */
    private $collectionRenderer;

    /**
     * @var DataTableHandler
     */
    private $dataTableHandler;

    /**
     * @param LanguagesRepository $languagesRepository
     * @param TagsSearcher $tagsSearcher
     * @param CollectionRenderer $collectionRenderer
     * @param DataTableHandler $dataTableHandler
     */
    public function __construct(
        LanguagesRepository $languagesRepository,
        TagsSearcher $tagsSearcher,
        CollectionRenderer $collectionRenderer,
        DataTableHandler $dataTableHandler
    ) {
        $this->languagesRepository = $languagesRepository;
        $this->tagsSearcher = $tagsSearcher;
        $this->collectionRenderer = $collectionRenderer;
        $this->dataTableHandler = $dataTableHandler;
    }

    /**
     * @return ViewContract
     */
    public function index(): ViewContract
    {
        $languages = $this->languagesRepository->getAll();
        $languages = $languages->sortBy('english_name');
        $languages = $languages->pluck('english_name', 'id');

        return view('backend.pages.tags.index', [
            'languages' => $languages,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     */
    public function search(Request $request): array
    {
        $this->collectionRenderer->addColumns([
            'id' => 'backend.pages.tags.search.columns.id',
            'name' => 'backend.pages.tags.search.columns.name',
            'page-count' => 'backend.pages.tags.search.columns.page-count',
            'actions' => 'backend.pages.tags.search.columns.actions',
        ]);

        return $this->dataTableHandler->handle(
            $this->tagsSearcher,
            $this->collectionRenderer,
            $request
        );
    }

}