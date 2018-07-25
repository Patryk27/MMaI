<?php

namespace App\Languages\Queries;

class GetLanguageByIdQuery implements LanguageQueryInterface
{

    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    public function __construct(
        int $id
    ) {
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