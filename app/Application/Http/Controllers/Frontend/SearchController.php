<?php

namespace App\Application\Http\Controllers\Frontend;

use App\Application\Http\Controllers\Controller;
use App\Core\Exceptions\Exception as CoreException;
use App\Core\Websites\WebsiteDetector;
use App\Pages\Exceptions\PageException;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\ValueObjects\RenderedPage;
use App\Search\Queries\Search;
use App\Application\Http\Requests\Frontend\Search as SearchRequest;
use App\Search\SearchFacade;
use Illuminate\Support\Collection;

class SearchController extends Controller {

    /** @var WebsiteDetector */
    private $websiteDetector;

    /** @var SearchFacade */
    private $searchFacade;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        WebsiteDetector $websiteDetector,
        SearchFacade $searchFacade,
        PagesFacade $pagesFacade
    ) {
        $this->websiteDetector = $websiteDetector;
        $this->searchFacade = $searchFacade;
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

        $pages = $this->searchFacade->search(
            new Search([
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
