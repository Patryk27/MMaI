<?php

namespace App\Core\Api;

use App\Core\Api\Searcher\ApiSearcherResponse;
use App\Core\Api\Searcher\Querier;
use App\Core\Api\Searcher\Renderer;
use App\Core\Exceptions\Exception as CoreException;
use Illuminate\Http\Request;

/**
 * This class is responsible for facilitating creating searching-related
 * controllers.
 *
 * For example usage please take a look into e.g.:
 *   - @see \App\Application\Http\Controllers\Api\PagesController
 *   - @see \App\Application\Http\Controllers\Api\TagsController
 */
final class ApiSearcher {

    /** @var Querier */
    private $querier;

    /** @var Renderer */
    private $renderer;

    public function __construct(Querier $querier, Renderer $renderer) {
        $this->querier = $querier;
        $this->renderer = $renderer;
    }

    /**
     * @param string $column
     * @param string $view
     * @return void
     */
    public function addColumn(string $column, string $view): void {
        $this->renderer->addColumn($column, $view);
    }

    /**
     * @param array $columns
     * @return void
     */
    public function addColumns(array $columns): void {
        foreach ($columns as $column => $view) {
            $this->addColumn($column, $view);
        }
    }

    /**
     * Sets function which will be used to count size of the query's result set.
     *
     * Query is built automatically and passed as the caller's argument; the
     * sole responsibility of the caller is to execute the query on appropriate
     * facade and return size of the result set.
     *
     * # Example
     *
     * ```php
     * $searcher->setCounter(function (array $query): int {
     *   return $this->someFacade->queryCount(new SearchQuery($query));
     * });
     * ```
     *
     * @param callable $counter
     * @return void
     */
    public function setCounter(callable $counter): void {
        $this->querier->setCounter($counter);
    }

    /**
     * Sets function which will be used to return the query's result set.
     *
     * Query is built automatically and passed as the caller's argument; the
     * sole responsibility of the caller is to execute the query on appropriate
     * facade and return the result set.
     *
     * # Example
     *
     * ```php
     * $searcher->setFetcher(function (array $query): Collection {
     *   return $this->someFacade->queryMany(new SearchQuery($query));
     * });
     * ```
     *
     * @param callable $fetcher
     * @return void
     */
    public function setFetcher(callable $fetcher): void {
        $this->querier->setFetcher($fetcher);
    }

    /**
     * # Example
     *
     * ```php
     * class MyController {
     *   // ... //
     *
     *   public function index(Request $request): ApiSearcherResponse {
     *     $this->apiSearcher->setCounter(// ... //);
     *     $this->apiSearcher->setFetcher(// ... //);
     *
     *     return $this->apiSearcher->search($request);
     *   }
     * }
     * ```
     *
     * @param Request $request
     * @return ApiSearcherResponse
     * @throws CoreException
     */
    public function search(Request $request): ApiSearcherResponse {
        $query = $this->extractQuery($request);

        $allCount = $this->querier->countAllItems();
        $matchingCount = $this->querier->countMatchingItems($query);
        $items = $this->querier->searchItems($query);

        if ($request->get('render', false)) {
            $items = $items->map([$this->renderer, 'render']);
        }

        return new ApiSearcherResponse([
            'allCount' => $allCount,
            'matchingCount' => $matchingCount,
            'items' => $items,
        ]);
    }

    /**
     * @param Request $request
     * @return array
     * @throws CoreException
     */
    private function extractQuery(Request $request): array {
        if (!$request->has('query')) {
            return [];
        }

        $query = json_decode($request->get('query'), true);

        if (empty($query)) {
            throw new CoreException('Given query is not a valid JSON.');
        }

        return $query;
    }

}
