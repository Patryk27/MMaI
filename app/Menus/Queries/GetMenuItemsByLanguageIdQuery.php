<?php

namespace App\Menus\Queries;

final class GetMenuItemsByLanguageIdQuery implements MenuItemsQuery
{
    /** @var int */
    private $languageId;

    public function __construct(int $languageId)
    {
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
