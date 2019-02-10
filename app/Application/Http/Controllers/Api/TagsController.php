<?php

namespace App\Application\Http\Controllers\Api;

use App\Application\Http\Controllers\Controller;
use App\Core\Api\ApiSearcher;
use App\Core\Api\Searcher\ApiSearcherResponse;
use App\Core\Exceptions\Exception as CoreException;
use App\Tags\Exceptions\TagException;
use App\Tags\Models\Tag;
use App\Tags\Queries\SearchTags;
use App\Tags\Requests\CreateTag;
use App\Tags\Requests\UpdateTag;
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
        $baseView = 'backend.components.table.columns.';
        $tagsView = 'backend.components.tags.table.columns.';

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
     * @return Tag
     * @throws TagException
     */
    public function store(CreateTag $request): Tag {
        return $this->tagsFacade->create($request);
    }

    /**
     * @param Tag $tag
     * @param UpdateTag $request
     * @return Tag
     * @throws TagException
     */
    public function update(Tag $tag, UpdateTag $request): Tag {
        $this->tagsFacade->update($tag, $request);
        return $tag;
    }

    /**
     * @param Tag $tag
     * @return void
     */
    public function destroy(Tag $tag): void {
        $this->tagsFacade->delete($tag);
    }

}
