<?php

namespace App\Grid\Query;

use App\Core\ValueObjects\HasInitializationConstructor;

final class GridQueryPagination {

    use HasInitializationConstructor;

    /** @var int */
    private $page;

    /** @var int */
    private $perPage;

    /**
     * @return int
     */
    public function getPage(): int {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getPerPage(): int {
        return $this->perPage;
    }

}
