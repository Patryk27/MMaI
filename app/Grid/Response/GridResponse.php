<?php

namespace App\Grid\Response;

use App\Core\ValueObjects\HasInitializationConstructor;
use Illuminate\Contracts\Support\Arrayable;

final class GridResponse implements Arrayable {

    use HasInitializationConstructor;

    /** @var int */
    private $totalCount;

    /** @var int */
    private $matchingCount;

    /** @var array */
    private $items;

    /**
     * {@inheritdoc}
     */
    public function toArray() {
        return [
            'totalCount' => $this->totalCount,
            'matchingCount' => $this->matchingCount,
            'items' => $this->items,
        ];
    }

}
