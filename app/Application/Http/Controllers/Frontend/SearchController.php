<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Frontend\SearchRequest;
use App\Core\Exceptions\Exception as CoreException;
use App\Core\Websites\WebsiteDetector;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\ValueObjects\RenderedPage;
use App\SearchEngine\Queries\SearchQuery;
use App\SearchEngine\SearchEngineFacade;
use Illuminate\Support\Collection;

class SearchController extends Controller {
    /** @var WebsiteDetector */
    private $websiteDetector;

    /** @var SearchEngineFacade */
    private $searchEngineFacade;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        WebsiteDetector $websiteDetector,
        SearchEngineFacade $searchEngineFacade,
        PagesFacade $pagesFacade
    ) {
        $this->websiteDetector = $websiteDetector;
        $this->searchEngineFacade = $searchEngineFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @return mixed
     */
    public function index() {
        return view('frontend.views.search.index');
    }

    /**
     * @param SearchRequest $request
     * @return mixed
     * @throws PageException
     * @throws CoreException
     */
    public function search(SearchRequest $request) {
        $website = $this->websiteDetector->detectOrFail($request);

        /** @var Collection|Page[] $pages */
        $pages = $this->searchEngineFacade->search(
            new SearchQuery([
                'query' => $request->get('query'),
                'type' => Page::TYPE_POST,
                'website' => $website,
            ])
        );

        /** @var Collection|RenderedPage[] $pages */
        $renderedPages = $pages->map([$this->pagesFacade, 'render']);

        return view('frontend.views.search', [
            'query' => $request->get('query'),
            'renderedPages' => $renderedPages,
        ]);
    }
}
