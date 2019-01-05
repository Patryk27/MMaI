<?php

namespace App\Core\Api\Searcher;

use App\Core\ValueObjects\HasInitializationConstructor;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

final class ApiSearcherResponse implements Responsable {
    use HasInitializationConstructor;

    /** @var int */
    private $allCount;

    /** @var int */
    private $matchingCount;

    /** @var array[] */
    private $items;

    /**
     * Returns number of all the items (e.g. tags) there are.
     *
     * @return int
     */
    public function getAllCount(): int {
        return $this->allCount;
    }

    /**
     * Returns number of all the matching items (e.g. tags), when any filter has
     * been specified (otherwise it's just "all count" anyway).
     *
     * @return int
     */
    public function getMatchingCount(): int {
        return $this->matchingCount;
    }

    /**
     * Returns all the matching items.
     *
     * @return array[]
     */
    public function getItems(): array {
        return $this->items;
    }

    /**
     * @inheritDoc
     */
    public function toResponse($request) {
        return JsonResponse::create([
            'allCount' => $this->allCount,
            'matchingCount' => $this->matchingCount,
            'items' => $this->items,
        ]);
    }
}
