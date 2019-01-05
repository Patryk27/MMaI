<?php

namespace App\Menus\Implementation\Repositories;

use App\Core\Repositories\EloquentRepository;
use App\Menus\Models\MenuItem;
use Illuminate\Support\Collection;

class EloquentMenuItemsRepository implements MenuItemsRepository {
    /** @var EloquentRepository */
    private $repository;

    public function __construct(MenuItem $menuItem) {
        $this->repository = new EloquentRepository($menuItem);
    }

    /**
     * @inheritDoc
     */
    public function getByWebsiteId(int $websiteId): Collection {
        return $this->repository->getByMany('website_id', $websiteId);
    }
}
