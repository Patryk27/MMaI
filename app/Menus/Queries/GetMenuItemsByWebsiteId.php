<?php

namespace App\Menus\Queries;

final class GetMenuItemsByWebsiteId implements MenuItemsQuery {

    /** @var int */
    private $websiteId;

    public function __construct(int $websiteId) {
        $this->websiteId = $websiteId;
    }

    /**
     * @return int
     */
    public function getWebsiteId(): int {
        return $this->websiteId;
    }

}
