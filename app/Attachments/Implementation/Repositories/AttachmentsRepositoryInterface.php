<?php

namespace App\Attachments\Implementation\Repositories;

use App\Attachments\Models\Attachment;
use Illuminate\Support\Collection;

interface AttachmentsRepositoryInterface
{

    /**
     * Returns attachment with given id or `null` if no such attachment exists.
     *
     * @param int $id
     * @return Attachment|null
     */
    public function getById(int $id): ?Attachment;

    /**
     * Returns all the attachments.
     *
     * @return Collection|Attachment[]
     */
    public function getAll(): Collection;

    /**
     * Returns all the unbound attachments.
     *
     * @return Collection|Attachment[]
     */
    public function getAllUnbound(): Collection;

    /**
     * Saves given attachment in the database.
     *
     * @param Attachment $attachment
     * @return void
     */
    public function persist(Attachment $attachment): void;

    /**
     * Removes given attachment from the database.
     *
     * @param Attachment $attachment
     * @return void
     */
    public function delete(Attachment $attachment): void;

}
