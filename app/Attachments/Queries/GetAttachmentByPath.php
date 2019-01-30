<?php

namespace App\Attachments\Queries;

final class GetAttachmentByPath implements AttachmentsQuery {

    /** @var string */
    private $path;

    public function __construct(string $path) {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string {
        return $this->path;
    }

}
