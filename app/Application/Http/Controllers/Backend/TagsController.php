<?php

namespace App\Application\Http\Controllers\Backend;

use App\Application\Http\Controllers\Controller;
use App\Websites\Exceptions\WebsiteException;
use App\Websites\Queries\GetAllWebsitesQuery;
use App\Websites\WebsitesFacade;
use Illuminate\Contracts\View\View;

class TagsController extends Controller
{
    /** @var WebsitesFacade */
    private $websitesFacade;

    public function __construct(WebsitesFacade $websitesFacade)
    {
        $this->websitesFacade = $websitesFacade;
    }

    /**
     * @return View
     * @throws WebsiteException
     */
    public function index(): View
    {
        $websites = $this->websitesFacade->queryMany(
            new GetAllWebsitesQuery()
        );

        return view('backend.views.tags.index', [
            'websites' => $websites->sortBy('name')->pluck('name', 'id'),
        ]);
    }
}
