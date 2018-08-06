<?php

namespace App\Languages\Queries;

/**
 * This class defines a query which will return single language with specified
 * id.
 */
final class GetLanguageByIdQuery implements LanguagesQueryInterface
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