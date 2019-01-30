<?php

namespace App\Application\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;
use App\Application\Http\Requests\Backend\Tags\CreateTag;
use App\Application\Http\Requests\Backend\Tags\UpdateTag;
use App\Core\Api\ApiSearcher;
use App\Core\Api\Searcher\ApiSearcherResponse;
use App\Core\Exceptions\Exception as CoreException;
use App\Tags\Exceptions\TagException;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTags;
use App\Tags\TagsFacade;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class TagsController extends Controller {

    /** @var ApiSearcher */
    private $apiSearcher;

    /** @var TagsFacade */
    private $tagsFacade;

    public function __construct(
        ApiSearcher $apiSearcher,
        TagsFacade $tagsFacade
    ) {
        $this->apiSearcher = $apiSearcher;
        $this->tagsFacade = $tagsFacade;
    }

    /**
     * @param Request $request
     * @return ApiSearcherResponse
     * @throws CoreException
     */
    public function index(Request $request): ApiSearcherResponse {
        $baseView = 'backend.components.table.';
        $tagsView = 'backend.components.tags.table.';

        $this->apiSearcher->addColumns([
            'id' => $baseView . 'id',
            'name' => $tagsView . 'name',
            'assignedPagesCount' => $tagsView . 'assigned-pages-count',
            'createdAt' => $baseView . 'created-at',
            'actions' => $tagsView . 'actions',
        ]);

        $this->apiSearcher->setCounter(function (array $query): int {
            return $this->tagsFacade->queryCount(
                new SearchTags($query)
            );
        });

        $this->apiSearcher->setFetcher(function (array $query): Collection {
            return $this->tagsFacade->queryMany(
                new SearchTags($query)
            );
        });

        return $this->apiSearcher->search($request);
    }

    /**
     * @param CreateTag $request
     * @return void
     * @throws TagException
     */
    public function store(CreateTag $request): void {
        $this->tagsFacade->create(
            $request->all()
        );
    }

    /**
     * @param UpdateTag $request
     * @param Tag $tag
     * @return void
     * @throws TagException
     */
    public function update(UpdateTag $request, Tag $tag): void {
        $this->tagsFacade->update(
            $tag,
            $request->all()
        );
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function destroy(Tag $tag): void {
        $this->tagsFacade->delete($tag);
    }

}