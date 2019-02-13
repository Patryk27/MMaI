<?php

namespace App\Search\Events;

use App\Search\Queries\Search;
use Illuminate\Queue\SerializesModels;

final class QueryPerformed {

    use SerializesModels;

    /** @var Search */
    private $query;

    /** @var int[] */
    private $matchedIds;

    /**
     * @param Search $query
     * @param int[] $matchedIds
     */
    public function __construct(Search $query, array $matchedIds) {
        $this->query = $query;
        $this->matchedIds = $matchedIds;
    }

    /**
     * @return Search
     */
    public function getQuery(): Search {
        return $this->query;
    }

    /**
     * @return int[]
     */
    public function getMatchedIds(): array {
        return $this->matchedIds;
    }

}
