<?php

namespace App\Application\Interfaces\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;
use App\Grid\GridFacade;
use App\Grid\Requests\GridRequest;
use App\Grid\Response\GridResponse;
use App\Grid\Schema\GridSchema;
use App\Pages\Models\Page;
use App\Pages\PagesFacade;
use App\Pages\Requests\CreatePage;
use App\Pages\Requests\UpdatePage;
use App\Websites\Exceptions\WebsiteException;

class PagesController extends Controller {

    /** @var GridFacade */
    private $gridFacade;

    /** @var PagesFacade */
    private $pagesFacade;

    public function __construct(
        GridFacade $gridFacade,
        PagesFacade $pagesFacade
    ) {
        $this->gridFacade = $gridFacade;
        $this->pagesFacade = $pagesFacade;
    }

    /**
     * @param GridRequest $request
     * @return GridResponse
     */
    public function index(GridRequest $request): GridResponse {
        return $this->pagesFacade->executeGridQuery(
            $this->gridFacade->prepareQuery($request)
        );
    }

    /**
     * @return GridSchema
     * @throws WebsiteException
     */
    public function grid(): GridSchema {
        return $this->pagesFacade->prepareGridSchema();
    }

    /**
     * @param CreatePage $request
     * @return array
     */
    public function store(CreatePage $request): array {
        $page = $this->pagesFacade->create($request);

        return [
            'redirectTo' => $page->getEditUrl(),
        ];
    }

    /**
     * @param Page $page
     * @param UpdatePage $request
     * @return array
     */
    public function update(Page $page, UpdatePage $request): array {
        $this->pagesFacade->update($page, $request);

        return [
            'redirectTo' => $page->getEditUrl(),
        ];
    }

}
