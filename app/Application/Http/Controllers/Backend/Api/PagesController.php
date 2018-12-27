<?php

namespace App\Application\Http\Controllers\Backend\Api;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Pages\CreatePageRequest;
use App\Application\Http\Requests\Backend\Pages\UpdatePageRequest;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;

class PagesController extends Controller
{
    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(PagesFacade $pagesFacade)
    {
        $this->pagesFacade = $pagesFacade;
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
