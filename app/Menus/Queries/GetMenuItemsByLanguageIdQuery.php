<?php

namespace App\Menus\Queries;

class GetMenuItemsByLanguageIdQuery implements MenuQueryInterface
{

    /**
     * @var int
     */
    private $languageId;

    /**
     * @param int $languageId
     */
    public function __construct(
        int $languageId
    ) {
        $this->languageId = $languageId;
    }

    /**
     * @return int
     */
    public function getLanguageId(): int
    {
        return $this->languageId;
    }

}