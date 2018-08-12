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
     * Returns tag with specified language id and name, or `null` if no such tag
     * exists.
     *
     * @param int $languageId
     * @param string $name
     * @return Tag|null
     */
    public function getByLanguageIdAndName(int $languageId, string $name): ?Tag;

    /**
     * Returns all the tags.
     *
     * @return Collection|Tag[]
     */
    public function getAll(): Collection;

    /**
     * Saves given tag in the database.
     *
     * @param Tag $tag
     * @return void
     */
    public function persist(Tag $tag): void;

    /**
     * Removes given tag from the database.
     *
     * @param Tag $tag
     * @return void
     */
    public function delete(Tag $tag): void;

}