<?php

namespace App\Attachments\Queries;

final class GetAttachmentById implements AttachmentsQuery {

    /** @var int */
    private $id;

    public function __construct(int $id) {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

}
