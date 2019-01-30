<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Pages\Models\Page;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Queries\GetAllWebsites;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\View\View;

class PagesController extends Controller {

    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(WebsitesFacade $websitesFacade) {
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @return View
     * @throws WebsiteException
     */
    public function index(): View {
        $websites = $this->websitesFacade->queryMany(
            new GetAllWebsites()
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
     * @return View
     */
    public function createPage(): View {
        return view('backend.views.pages.create', [
            'type' => Page::TYPE_PAGE,
        ]);
    }

    /**
     * @return View
     */
    public function createPost(): View {
        return view('backend.views.pages.create', [
            'type' => Page::TYPE_POST,
        ]);
    }

    /**
     * @param Page $page
     * @return View
     */
    public function edit(Page $page): View {
        return view('backend.views.pages.edit', [
            'page' => $page,
        ]);
    }

}
