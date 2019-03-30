<?php

namespace App\Application\Interfaces\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Queries\GetAllWebsites;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\View\View;

class TagsController extends Controller {

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

        return view('backend.views.tags.index', [
            'websites' => $websites->sortBy('name')->pluck('name', 'id'),
        ]);
    }

}
