<?php

namespace App\Pages\Queries;

/**
 * Defines a query which will return all page variants with specified ids.
 * Returned page variants will have the same order as the specified ids have.
 */
final class GetPageVariantsByIdsQuery implements PageVariantsQuery
{

    /**
     * @var int[]
     */
    private $ids;

    /**
     * @param array $ids
     */
    public function __construct(
        array $ids
    ) {
        $this->ids = $ids;
    }

    /**
     * @return int[]
     */
    public function getIds(): array
    {
        return $this->ids;
    }

}
