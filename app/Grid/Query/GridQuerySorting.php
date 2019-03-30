<?php

namespace App\Grid\Query;

use App\Core\ValueObjects\HasInitializationConstructor;

final class GridQuerySorting {

    use HasInitializationConstructor;

    /** @var string */
    private $field;

    /** @var string */
    private $direction;

    /**
     * @return string
     */
    public function getField(): string {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDirection(): string {
        return $this->direction;
    }

}
