<?php

namespace App\Pages\Queries;

final class GetPagesByIdsQuery implements PagesQuery {
    /** @var int[] */
    private $ids;

    /**
     * @param int[] $ids
     */
    public function __construct(array $ids) {
        $this->ids = $ids;
    }

    /**
     * @return int[]
     */
    public function getIds(): array {
        return $this->ids;
    }
}
