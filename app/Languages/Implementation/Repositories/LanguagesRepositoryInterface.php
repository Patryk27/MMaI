<?php

namespace App\Languages\Implementation\Repositories;

use App\Languages\Models\Language;
use Illuminate\Support\Collection;

interface LanguagesRepositoryInterface
{

    /**
     * Returns language with specified id.
     *
     * @param int $id
     * @return Language|null
     */
    public function getById(int $id): ?Language;

    /**
     * Returns language with specified slug.
     *
     * @param string $slug
     * @return Language|null
     */
    public function getBySlug(string $slug): ?Language;

    /**
     * Returns all the languages.
     *
     * @return Collection|Language[]
     */
    public function getAll(): Collection;

}