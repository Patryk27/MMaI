<?php

namespace App\Attachments\Implementation\Repositories;

use App\Attachments\Models\Attachment;
use Illuminate\Support\Collection;

interface AttachmentsRepository {

    /**
     * Returns attachment with given id or `null` if no such attachment exists.
     *
     * @param int $id
     * @return Attachment|null
     */
    public function getById(int $id): ?Attachment;

    /**
     * Returns attachment with given path or `null` if no such attachment
     * exists.
     *
     * @param string $path
     * @return Attachment|null
     */
    public function getByPath(string $path): ?Attachment;

    /**
     * Returns all the attachments.
     *
     * @return Collection|Attachment[]
     */
    public function getAll(): Collection;

    /**
     * Returns all the attachments that have not been bound to a page.
     *
     * @return Collection|Attachment[]
     */
    public function getDetached(): Collection;

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
