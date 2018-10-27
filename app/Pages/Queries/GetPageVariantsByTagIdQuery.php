<?php

namespace App\Pages\Queries;

/**
 * Defines a query which will return all page variants assigned to given tag.
 */
final class GetPageVariantsByTagIdQuery implements PageVariantsQuery
{

    /**
     * @var int
     */
    private $tagId;

    /**
     * @param int $tagId
     */
    public function __construct(
        int $tagId
    ) {
        $this->tagId = $tagId;
    }

    /**
     * @return int
     */
    public function getTagId(): int
    {
        return $this->tagId;
    }

}
