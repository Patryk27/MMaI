<?php

namespace App\SearchEngine\Events;

use App\SearchEngine\Queries\SearchQuery;
use Illuminate\Queue\SerializesModels;

final class QuerySearched
{

    use SerializesModels;

    /**
     * @var SearchQuery
     */
    private $query;

    /**
     * @var int[]
     */
    private $matchedIds;

    /**
     * @param SearchQuery $query
     * @param array $matchedIds
     */
    public function __construct(
        SearchQuery $query,
        array $matchedIds
    ) {
        $this->query = $query;
        $this->matchedIds = $matchedIds;
    }

    /**
     * @return SearchQuery
     */
    public function getQuery(): SearchQuery
    {
        return $this->query;
    }

    /**
     * @return int[]
     */
    public function getMatchedIds(): array
    {
        return $this->matchedIds;
    }

}
