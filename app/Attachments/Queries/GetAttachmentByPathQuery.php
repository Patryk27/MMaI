<?php

namespace App\Attachments\Queries;

final class GetAttachmentByPathQuery implements AttachmentsQueryInterface
{

    /**
     * @var string
     */
    private $path;

    /**
     * @param string $path
     */
    public function __construct(
        string $path
    ) {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

}
