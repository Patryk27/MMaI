<?php

namespace App\Websites\Implementation\Repositories;

use App\Websites\Models\Website;
use Illuminate\Support\Collection;

interface WebsitesRepository {
    /**
     * Returns website with specified id.
     *
     * @param int $id
     * @return Website|null
     */
    public function getById(int $id): ?Website;

    /**
     * Returns website with specified slug.
     *
     * @param string $slug
     * @return Website|null
     */
    public function getBySlug(string $slug): ?Website;

    /**
     * Returns all the websites.
     *
     * @return Collection|Website[]
     */
    public function getAll(): Collection;
}
