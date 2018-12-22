<?php

namespace App\Tags\Queries;

final class GetTagByIdQuery implements TagsQuery
{
    /** @var int */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
}
