<?php

namespace App\Tags\Implementation\Repositories;

use App\Tags\Models\Tag;
use Illuminate\Support\Collection;

interface TagsRepositoryInterface
{

    /**
     * Returns tag with specified id or `null` if no such tag exists.
     *
     * @param int $id
     * @return Tag|null
     */
    public function getById(int $id): ?Tag;

    /**
     * Returns all the tags.
     *
     * @return Collection|Tag[]
     */
    public function getAll(): Collection;

}