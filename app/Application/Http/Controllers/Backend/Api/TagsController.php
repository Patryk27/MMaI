<?php

namespace App\Application\Http\Controllers\Backend\Api;

use App\Application\Http\Controllers\Controller;
use App\Tags\Queries\SearchTagsQuery;
use App\Tags\TagsFacade;
use Exception;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    /** @var TagsFacade */
    private $tagsFacade;

    public function __construct(TagsFacade $tagsFacade)
    {
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @param Request $request
     * @return array
     * @throws Exception
     */
    public function index(Request $request): array
    {
        return $this->tagsFacade->queryMany(
            SearchTagsQuery::fromRequest($request)
        )->all();
    }
}
