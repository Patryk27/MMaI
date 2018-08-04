<?php

namespace App\Menus\Queries;

/**
 * This class defines a query which will return all the menu items for specified
 * language.
 */
final class GetMenuItemsByLanguageIdQuery implements MenuItemsQueryInterface
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