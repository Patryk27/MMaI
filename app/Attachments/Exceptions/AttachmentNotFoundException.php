<?php

namespace App\Attachments\Exceptions;

class AttachmentNotFoundException extends AttachmentException {
    public function __construct() {
        parent::__construct('Attachment was not found.');
    }
}
