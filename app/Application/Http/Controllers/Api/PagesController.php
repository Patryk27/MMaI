<?php

namespace App\Application\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Pages\CreatePageRequest;
use App\Application\Http\Requests\Backend\Pages\UpdatePageRequest;
use App\Core\Api\ApiSearcher;
use App\Core\Api\Searcher\ApiSearcherResponse;
use App\Core\Exceptions\Exception as CoreException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Queries\SearchPages;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PagesController extends Controller
{
    /** @var ApiSearcher */
    private $apiSearcher;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        ApiSearcher $apiSearcher,
        PagesFacade $pagesFacade
    ) {
        $this->apiSearcher = $apiSearcher;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @param Request $request
     * @return ApiSearcherResponse
     * @throws CoreException
     */
    public function index(Request $request): ApiSearcherResponse
    {
        $baseView = 'backend.components.table.';
        $pagesView = 'backend.components.pages.table.';

        $this->apiSearcher->addColumns([
            'id' => $baseView . 'id',
            'website' => $pagesView . 'website',
            'type' => $pagesView . 'type',
            'title' => $pagesView . 'title',
            'status' => $pagesView . 'status',
            'createdAt' => $baseView . 'created-at',
            'actions' => $pagesView . 'actions',
        ]);

        $this->apiSearcher->setCounter(function (array $query): int {
            return $this->pagesFacade->queryCount(new SearchPages($query));
        });

        $this->apiSearcher->setFetcher(function (array $query): Collection {
            return $this->pagesFacade->queryMany(new SearchPages($query));
        });

        return $this->apiSearcher->search($request);
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
